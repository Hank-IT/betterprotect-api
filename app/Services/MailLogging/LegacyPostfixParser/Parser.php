<?php

namespace App\Services\MailLogging\LegacyPostfixParser;

use App\Services\BetterprotectPolicy\Handler\ClientSenderAccessHandler;
use Illuminate\Support\Str;

class Parser
{
    protected $encryptionIndex = [];

    public function parse($logs)
    {
        $messages = [];
        foreach($logs as $key => $log) {
            $message = trim($log->Message);

            // We can't get any direct association from the log between the lines which suggest
            // encryption and the actually transmitted email. We just create an index
            // of every encrypted connection and match the email client or relay.
            preg_match('/^(?<enc_type>Untrusted|Anonymous|Trusted|Verified) TLS connection established (?<enc_direction>from|to) [^,]*\[(?<enc_ip>.*)\]:(?:.*:)? (?<enc_cipher>[^,]*)$/', $message, $result);

            if (! empty($result)) {
                $this->encryptionIndex[$key]['enc_type'] = $result['enc_type'];
                $this->encryptionIndex[$key]['enc_direction'] = $result['enc_direction'];
                $this->encryptionIndex[$key]['enc_ip'] = $result['enc_ip'];
                $this->encryptionIndex[$key]['enc_cipher'] = $result['enc_cipher'];
                $this->encryptionIndex[$key]['process'] = $log->SysLogTag;
            }

            // Match daemon
            preg_match('/postfix\/(?<daemon>[\w]+)/', $log->SysLogTag, $daemon);

            if (isset($daemon['daemon'])) {
                switch ($daemon['daemon']) {
                    case 'qmgr':
                        preg_match('/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): ?from=<?(?<from>[^>,]*)>?, size=(?<size>[0-9]+), nrcpt=(?<nrcpt>[0-9]+)/', $message, $result);

                        if (! empty($result)) {
                            if (isset($result['queue_id'])) $messages[$result['queue_id']]['queue_id'] = $result['queue_id'];
                            if (isset($result['from'])) $messages[$result['queue_id']]['from'] = $result['from'];
                            if (isset($result['size'])) $messages[$result['queue_id']]['size'] = $result['size'];
                            if (isset($result['nrcpt'])) $messages[$result['queue_id']]['nrcpt'] = $result['nrcpt'];
                            if (isset($result['host'])) $messages[$result['queue_id']]['host'] = $result['host'];
                            $messages[$result['queue_id']]['reported_at'] = $log->DeviceReportedTime;
                        }
                        break;
                    case 'bounce':
                        preg_match('/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): (?:sender non-delivery notification): (?<ndn_queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11})/', $message, $result);

                        if (! empty($result)) {
                            if (isset($result['ndn_queue_id'])) $messages[$result['queue_id']]['ndn_queue_id'] = $result['ndn_queue_id'];
                        }
                        break;
                    case 'smtp':
                        preg_match('/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): ?to=<?(?<to>[^>,]*)>?, (?:orig_to=<?([^>,]*)>?, )?relay=(?<relay>[^,]*\[(?<relay_ip>.*)\]:[0-9]+), (?:conn_use=([0-9]+), )?delay=(?<delay>[^,]+), delays=(?<delays>[^,]+), dsn=(?<dsn>[^,]+), status=(?<status>.*?) \((?<response>.*)\)$/', $message, $result);

                        if (empty($result)) {
                            preg_match('/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): ?to=<?(?<to>[^>,]*)>?, ?relay=(?<relay>[^,]*), ?delay=(?<delay>[^,]+), delays=(?<delays>[^,]+), dsn=(?<dsn>[^,]+), status=(?<status>.*?) \((?<response>.*)\)$/', $message, $result);

                            if (! empty($result)) {
                                if (isset($result['queue_id'])) $messages[$result['queue_id']]['queue_id'] = $result['queue_id'];
                                if (isset($result['to'])) $messages[$result['queue_id']]['to'] = $result['to'];
                                if (isset($result['relay'])) $messages[$result['queue_id']]['relay'] = $result['relay'];
                                if (isset($result['delay'])) $messages[$result['queue_id']]['delay'] = $result['delay'];
                                if (isset($result['delays'])) $messages[$result['queue_id']]['delays'] = $result['delays'];
                                if (isset($result['dsn'])) $messages[$result['queue_id']]['dsn'] = $result['dsn'];
                                if (isset($result['status'])) $messages[$result['queue_id']]['status'] = Str::lower($result['status']);
                                if (isset($result['response'])) $messages[$result['queue_id']]['response'] = $result['response'];
                                $messages[$result['queue_id']]['host'] = $log->FromHost;
                            }
                        } else {
                            if (isset($result['queue_id'])) $messages[$result['queue_id']]['queue_id'] = $result['queue_id'];
                            if (isset($result['to'])) $messages[$result['queue_id']]['to'] = $result['to'];
                            if (isset($result['relay'])) $messages[$result['queue_id']]['relay'] = $result['relay'];
                            if (isset($result['relay_ip'])) $messages[$result['queue_id']]['relay_ip'] = $result['relay_ip'];
                            if (isset($result['delay'])) $messages[$result['queue_id']]['delay'] = $result['delay'];
                            if (isset($result['delays'])) $messages[$result['queue_id']]['delays'] = $result['delays'];
                            if (isset($result['dsn'])) $messages[$result['queue_id']]['dsn'] = $result['dsn'];
                            if (isset($result['status'])) $messages[$result['queue_id']]['status'] = Str::lower($result['status']);
                            if (isset($result['response'])) $messages[$result['queue_id']]['response'] = $result['response'];
                            $messages[$result['queue_id']]['host'] = $log->FromHost;

                            if (! empty($encryption = optional($this->matchEncryptionIndex($messages[$result['queue_id']]['relay_ip'], $log->SysLogTag))[0])) {
                                $messages[$result['queue_id']] = array_merge($messages[$result['queue_id']], $encryption);
                            }
                        }
                        break;
                    case 'relay':
                        preg_match('/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): ?to=<?(?<to>[^>,]*)>?, ?relay=(?<relay>[^,]*), ?delay=(?<delay>[^,]+), delays=(?<delays>[^,]+), dsn=(?<dsn>[^,]+), status=(?<status>.*?) \((?<response>.*)\)$/', $message, $result);

                        if (! empty($result)) {
                            if (isset($result['queue_id'])) $messages[$result['queue_id']]['queue_id'] = $result['queue_id'];
                            if (isset($result['to'])) $messages[$result['queue_id']]['to'] = $result['to'];
                            if (isset($result['relay'])) $messages[$result['queue_id']]['relay'] = $result['relay'];
                            if (isset($result['delay'])) $messages[$result['queue_id']]['delay'] = $result['delay'];
                            if (isset($result['delays'])) $messages[$result['queue_id']]['delays'] = $result['delays'];
                            if (isset($result['dsn'])) $messages[$result['queue_id']]['dsn'] = $result['dsn'];
                            if (isset($result['status'])) $messages[$result['queue_id']]['status'] = Str::lower($result['status']);
                            if (isset($result['response'])) $messages[$result['queue_id']]['response'] = $result['response'];
                            $messages[$result['queue_id']]['host'] = $log->FromHost;
                        }
                        break;
                    case 'smtpd':
                        preg_match('/^(?<queue_id>NOQUEUE|[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): (?:milter-)?(?<status>.*): (?:RCPT|END-OF-MESSAGE) from (?<client>[^,]*\[(?<client_ip>.*)\]): (?<response>.*?); from=<?(?<from>[^>,]*)>? to=<?(?<to>[^>,]*)>? proto=(?<proto>.*?) helo=<?(?<helo>[^>,]*)>$/', $message, $result);

                        if (empty($result)) {
                            preg_match('/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): ?client=(?<client>[^,]*\[(?<client_ip>.*)\]+)$/', $message, $result);

                            if (! empty($result)) {
                                if (isset($result['queue_id'])) $messages[$result['queue_id']]['queue_id'] = $result['queue_id'];
                                if (isset($result['client'])) $messages[$result['queue_id']]['client'] = $result['client'];
                                if (isset($result['client_ip'])) $messages[$result['queue_id']]['client_ip'] = $result['client_ip'];
                                $messages[$result['queue_id']]['host'] = $log->FromHost;
                                $messages[$result['queue_id']]['reported_at'] = $log->DeviceReportedTime;

                                if (! empty($encryption = optional($this->matchEncryptionIndex($messages[$result['queue_id']]['client_ip'], $log->SysLogTag))[0])) {
                                    $messages[$result['queue_id']] = array_merge($messages[$result['queue_id']], $encryption);
                                }
                            }
                        } else {
                            $queueId = optional($result)['queue_id'] == 'NOQUEUE' ? strtoupper(uniqid()): $result['queue_id'];

                            if (isset($result['queue_id'])) $messages[$result['queue_id']]['queue_id'] = $result['queue_id'];
                            if (isset($result['client'])) $messages[$queueId]['client'] = $result['client'];
                            if (isset($result['client_ip'])) $messages[$queueId]['client_ip'] = $result['client_ip'];
                            if (isset($result['status'])) $messages[$queueId]['status'] = Str::lower($result['status']);
                            if (isset($result['from'])) $messages[$queueId]['from'] = $result['from'];
                            if (isset($result['to'])) $messages[$queueId]['to'] = $result['to'];
                            if (isset($result['proto'])) $messages[$queueId]['proto'] = $result['proto'];
                            if (isset($result['helo'])) $messages[$queueId]['helo'] = $result['helo'];
                            $messages[$queueId]['host'] = $log->FromHost;
                            $messages[$queueId]['reported_at'] = $log->DeviceReportedTime;

                            if (isset($result['response'])) {
                                $messages[$queueId]['response'] = $result['response'];

                                if (Str::contains($messages[$queueId]['response'], ClientSenderAccessHandler::POLICY_DENIED)) {
                                    $messages[$queueId]['bp_policy_decision'] = 'reject';
                                }

                                if (Str::contains($messages[$queueId]['response'], ClientSenderAccessHandler::POLICY_GRANTED)) {
                                    $messages[$queueId]['bp_policy_decision'] = 'ok';
                                }
                            }

                            if (! empty($encryption = optional($this->matchEncryptionIndex($messages[$queueId]['client_ip'], $log->SysLogTag))[0])) {
                                $messages[$queueId] = array_merge($messages[$queueId], $encryption);
                            }
                        }
                        break;
                    case 'cleanup':
                        preg_match('/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): .*: END-OF-MESSAGE from (?<client>.*): (?<response>[0-9]\.[0-9]\.[0-9] (?<status>.*?), .*) from=<?(?<from>[^>,]*)>? to=<?(?<to>[^>,]*)>? proto=(?<proto>.*?) helo=<?(?<helo>[^>,]*)/', $message, $result);

                        if (empty($result)) {
                            preg_match('/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): warning: header subject: (?<subject>.*) from (?<client>.*); (?<from>.*) (?<to>.*) (?<proto>.*)$/i', $message, $result);

                            if (isset($result['queue_id'])) $messages[$result['queue_id']]['queue_id'] = $result['queue_id'];
                            if (isset($result['subject'])) {
                                $messages[$result['queue_id']]['subject'] = $result['subject'];

                                // ToDo
                                if (Str::startsWith(Str::lower($messages[$result['queue_id']]['subject']), ['=?utf-8?', '=?iso-', '=?Windows'])) {
                                    $messages[$result['queue_id']]['subject'] = mb_decode_mimeheader($messages[$result['queue_id']]['subject']);
                                }
                            }
                        } else {
                            if (count($result) <= 1) {
                                break;
                            }

                            if (isset($result['queue_id'])) $messages[$result['queue_id']]['queue_id'] = $result['queue_id'];
                            if (isset($result['client'])) $messages[$result['queue_id']]['client'] = $result['client'];
                            if (isset($result['response'])) $messages[$result['queue_id']]['response'] = $result['response'];
                            if (isset($result['status'])) $messages[$result['queue_id']]['status'] = Str::lower($result['status']);
                            if (isset($result['from'])) $messages[$result['queue_id']]['from'] = $result['from'];
                            if (isset($result['to'])) $messages[$result['queue_id']]['to'] = $result['to'];
                            if (isset($result['proto'])) $messages[$result['queue_id']]['proto'] = $result['proto'];
                            if (isset($result['helo'])) $messages[$result['queue_id']]['helo'] = $result['helo'];
                            $messages[$result['queue_id']]['host'] = $log->FromHost;
                        }
                        break;
                }
            }
        }

       return $messages;
    }

    protected function matchEncryptionIndex($ip, $syslogTag)
    {
        return array_filter(array_reverse($this->encryptionIndex), function($data) use($ip, $syslogTag) {
            return $data['process'] == $syslogTag && $data['enc_ip'] == $ip;
        });
    }
}

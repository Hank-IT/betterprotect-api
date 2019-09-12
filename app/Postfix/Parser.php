<?php

namespace App\Postfix;

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
            preg_match('/^(Untrusted|Anonymous|Trusted|Verified) TLS connection established (from|to) ([^,]*\[(.*)\]): ([^,]*)$/', trim($log->Message), $result);

            if (!empty($result)) {
                $this->encryptionIndex[$key]['enc_type'] = $result[1];
                $this->encryptionIndex[$key]['enc_direction'] = $result[2];
                $this->encryptionIndex[$key]['enc_ip'] = $result[4];
                $this->encryptionIndex[$key]['enc_cipher'] = $result[5];
                $this->encryptionIndex[$key]['process'] = $log->SysLogTag;
            }

            // Match daemon
            preg_match('/postfix\/([\w]+)/', $log->SysLogTag, $daemon);

            if (isset($daemon[1])) {
                switch ($daemon[1]) {
                    // ToDo
                    /*
                    case 'bounce':
                        $message = trim($log->Message);

                        preg_match('/^(?:([0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): )? (sender non-delivery notification: [0-9A-Za-z]{14,16}|[0-9A-F]{10,11}$)/', $message, $result);

                        if (isset($result[1])) {
                            $messages[$result[1]]['queue_id'] = optional($result)[1];
                            $messages[$result[1]]['response'] = optional($result)[2];
                        }

                        break;
                    */
                    case 'qmgr':
                        preg_match('/^(?:([0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): )?from=<?([^>,]*)>?, (?:size=([0-9]+), nrcpt=([0-9]+))/', $message, $result);

                        if (!empty($result)) {
                            $messages[$result[1]]['queue_id'] = optional($result)[1];
                            $messages[$result[1]]['from'] = optional($result)[2];
                            $messages[$result[1]]['size'] = optional($result)[3];
                            $messages[$result[1]]['nrcpt'] = optional($result)[4];
                            $messages[$result[1]]['host'] = $log->FromHost;
                            $messages[$result[1]]['reported_at'] = $log->DeviceReportedTime;
                        }

                        break;
                    case 'smtp':{
                        preg_match('/^(?:([0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): )?to=<?([^>,]*)>?, (?:orig_to=<?([^>,]*)>?, )?relay=([^,]*\[(.*)\]:[0-9]+), (?:conn_use=([0-9]+), )?delay=([^,]+), delays=([^,]+), dsn=([^,]+), status=(.*?) \((.*)\)$/', $message, $result);

                        if (!empty($result)) {
                            $messages[$result[1]]['queue_id'] = optional($result)[1];
                            $messages[$result[1]]['to'] = optional($result)[2];
                            $messages[$result[1]]['relay'] = optional($result)[4];
                            $messages[$result[1]]['relay_ip'] = optional($result)[5];
                            $messages[$result[1]]['delay'] = optional($result)[8];
                            $messages[$result[1]]['delays'] = optional($result)[9];
                            $messages[$result[1]]['dsn'] = optional($result)[9];
                            $messages[$result[1]]['status'] = optional($result)[10];
                            $messages[$result[1]]['response'] = optional($result)[11];
                            $messages[$result[1]]['host'] = $log->FromHost;
                            $messages[$result[1]]['reported_at'] = $log->DeviceReportedTime;

                            if (!empty($this->matchEncryptionIndex($messages[$result[1]]['relay_ip'], $log->SysLogTag)[0])) {
                                array_merge($messages[$result[1]], $this->matchEncryptionIndex($messages[$result[1]]['relay_ip'], $log->SysLogTag)[0]);
                            }
                        }
                        break;
                    }
                    case 'smtpd': {
                        if (Str::startsWith($message,'NOQUEUE:')) {
                            $id = strtoupper(uniqid());

                            preg_match('/NOQUEUE: ([a-z]+): RCPT from ([^,]*\[(.*)\]): (.*?); from=<?([^>,]*)>? to=<?([^>,]*)>? proto=(.*?) helo=<?([^>,]*)/', $message, $result);

                            $messages[$id]['status'] = optional($result)[1];
                            $messages[$id]['client'] = optional($result)[2];
                            $messages[$id]['client_ip'] = optional($result)[3];
                            $messages[$id]['response'] = optional($result)[4];
                            $messages[$id]['from'] = optional($result)[5];
                            $messages[$id]['to'] = optional($result)[6];
                            $messages[$id]['proto'] = optional($result)[7];
                            $messages[$id]['helo'] = optional($result)[8];
                            $messages[$id]['host'] = $log->FromHost;
                            $messages[$id]['reported_at'] = $log->DeviceReportedTime;

                            if (!empty($this->matchEncryptionIndex($messages[$id]['client_ip'], $log->SysLogTag)[0])) {
                                array_merge($messages[$id], $this->matchEncryptionIndex($messages[$id]['client_ip'], $log->SysLogTag)[0]);
                            }
                        } else {
                            preg_match('/^(?:([0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): )?client=([^,]*\[(.*)\]+)/', $message, $result);

                            if (!empty($result)) {
                                $messages[$result[1]]['queue_id'] = optional($result)[1];
                                $messages[$result[1]]['client'] = optional($result)[2];
                                $messages[$result[1]]['client_ip'] = optional($result)[3];
                                $messages[$result[1]]['host'] = $log->FromHost;
                                $messages[$result[1]]['reported_at'] = $log->DeviceReportedTime;

                                if (!empty($this->matchEncryptionIndex($messages[$result[1]]['client_ip'], $log->SysLogTag)[0])) {
                                    array_merge($messages[$result[1]], $this->matchEncryptionIndex($messages[$result[1]]['client_ip'], $log->SysLogTag)[0]);
                                }

                            }
                        }
                        break;
                    }
                    case 'cleanup': {
                        preg_match('/^(?:([0-9A-Za-z]{14,16}|[0-9A-F]{10,11})): (.*): (END-OF-MESSAGE from (.*)): (([0-9]\.[0-9]\.[0-9]) (.*), (.*)) from=<?([^>,]*)>? to=<?([^>,]*)>? proto=(.*?) helo=<?([^>,]*)/', $message, $result);

                        if (!empty($result)) {
                            $messages[$result[1]]['queue_id'] = optional($result)[1];
                            $messages[$result[1]]['client'] = optional($result)[4];
                            $messages[$result[1]]['response'] = optional($result)[5];
                            $messages[$result[1]]['status'] = strtolower(optional($result)[7]);
                            $messages[$result[1]]['from'] = strtolower(optional($result)[9]);
                            $messages[$result[1]]['to'] = strtolower(optional($result)[10]);
                            $messages[$result[1]]['proto'] = strtolower(optional($result)[11]);
                            $messages[$result[1]]['helo'] = strtolower(optional($result)[12]);
                            $messages[$result[1]]['host'] = $log->FromHost;
                        }
                        break;
                    }
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

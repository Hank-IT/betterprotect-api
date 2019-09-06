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
            // Match daemon
            preg_match('/postfix\/([\w]+)/', $log->SysLogTag, $daemon);

            // We can't get any direct association from the log between the lines which suggest
            // encryption and the actually transmitted email. We just create an index
            // of every encrypted connection and match the email client or relay.
            preg_match('/(.*?)\sTLS\sconnection\sestablished/', $log->Message, $encryptionLevel);

            if (isset($encryptionLevel[1])) {
                $this->encryptionIndex[$key]['type'] = trim($encryptionLevel[1]);
                $this->encryptionIndex[$key]['process'] = $log->SysLogTag;

                // Match direction
                preg_match('/\sTLS\sconnection\sestablished\s(.*?)\s/', $log->Message, $direction);
                !isset($direction[1]) ?: $this->encryptionIndex[$key]['direction'] = trim($direction[1]);

                // Match cipher
                preg_match('/\s([^:]+)$/', $log->Message, $cipher);
                !isset($cipher[1]) ?: $this->encryptionIndex[$key]['cipher'] = trim($cipher[1]);

                // Match ip
                preg_match('/\[(.*)\]/', $log->Message, $ip);
                !isset($ip[1]) ?: $this->encryptionIndex[$key]['ip'] = $ip[1];
            }

            if (isset($daemon[1])) {
                switch ($daemon[1]) {
                    case 'bounce':
                        $message = $log->Message;
                        $queueId = $this->getQueueId($message);

                        if (!empty($queueId)) {
                            preg_match('/:\s(.*)/', $message, $response);
                            !isset($response[1]) ?: $messages[$queueId]['response'] = $response[1];
                        }
                        break;
                    case 'qmgr':
                        $message = $log->Message;
                        $queueId = $this->getQueueId($message);

                        if (!empty($queueId)) {
                            // Match from
                            preg_match('/.*:\sfrom=<(.*)>/', $message, $from);
                            !isset($from[1]) ?: $messages[$queueId]['from'] = $from[1];

                            // Match size
                            preg_match('/size=(\d*)/', $message, $size);
                            !isset($size[1]) ?: $messages[$queueId]['size'] = $size[1];

                            // Match nrcpt
                            preg_match('/nrcpt=(\d*)/', $message, $nrcpt);
                            !isset($nrcpt[1]) ?: $messages[$queueId]['nrcpt'] = $nrcpt[1];

                            $messages[$queueId]['host'] = $log->FromHost;
                            $messages[$queueId]['queue_id'] = $queueId;
                        }
                        break;
                    case 'smtp':{
                        $message = $log->Message;
                        $queueId = $this->getQueueId($message);

                        if (!empty($queueId)) {
                            // Match to
                            preg_match('/to=<(.*?)>/', $message, $to);
                            !isset($to[1]) ?: $messages[$queueId]['to'] = $to[1];

                            // Match relay
                            preg_match('/relay=(.*?),/', $message, $relay);
                            !isset($relay[1]) ?: $messages[$queueId]['relay'] = $relay[1];

                            // Match encryption for relay
                            preg_match('/\[(.*)\]/', $messages[$queueId]['relay'], $ip);
                            $messages[$queueId]['encryption'] = optional($this->matchEncryptionIndex(optional($ip)[1], $log->SysLogTag))[0];

                            // Match delay
                            preg_match('/delay=(.*?),/', $message, $delay);
                            !isset($delay[1]) ?: $messages[$queueId]['delay'] = $delay[1];

                            // Match delays
                            preg_match('/delays=(.*?),/', $message, $delays);
                            !isset($delays[1]) ?: $messages[$queueId]['delays'] = $delays[1];

                            // Match dsn
                            preg_match('/dsn=(.*?),/', $message, $dsn);
                            !isset($dsn[1]) ?: $messages[$queueId]['dsn'] = $dsn[1];

                            // Match status
                            preg_match('/status=([a-zA-Z0-9]+)/', $message, $status);
                            !isset($status[1]) ?: $messages[$queueId]['status'] = $status[1];

                            // Match response
                            preg_match('/\s\((.*)\)/', $message, $response);
                            !isset($response[1]) ?: $messages[$queueId]['response'] = $response[1];

                            $messages[$queueId]['host'] = $log->FromHost;
                            $messages[$queueId]['queue_id'] = $queueId;
                        }
                        break;
                    }
                    case 'smtpd': {
                        $message = $log->Message;

                        if (Str::startsWith(trim($message),'NOQUEUE:')) {
                            $id = strtoupper(uniqid());

                            // Match status
                            preg_match('/\s([a-z]+):\s/', $message, $status);
                            !isset($status[1]) ?: $messages[$id]['status'] = $status[1];

                            // Match client
                            preg_match('/:\sRCPT\sfrom\s(.*?):/', $message, $client);
                            !isset($client[1]) ?: $messages[$id]['client'] = $client[1];

                            // Match encryption for client
                            preg_match('/\[(.*)\]/', $messages[$id]['client'], $ip);
                            $messages[$id]['encryption'] = optional($this->matchEncryptionIndex(optional($ip)[1], $log->SysLogTag))[0];

                            // Match response
                            preg_match('/;\s(.*?);\sfrom/', $message, $response);
                            !isset($response[1]) ?: $messages[$id]['response'] = $response[1];

                            // Match from
                            preg_match('/from=<(.*?)>/', $message, $from);
                            !isset($from[1]) ?: $messages[$id]['from'] = $from[1];

                            // Match to
                            preg_match('/to=<(.*?)>/', $message, $to);
                            !isset($to[1]) ?: $messages[$id]['to'] = $to[1];

                            preg_match('/]:\s(.*);/', $message, $response);
                            !isset($response[1]) ?: $messages[$id]['response'] = $response[1];

                            // Match proto
                            preg_match('/proto=(.*)\s/', $message, $proto);
                            !isset($proto[1]) ?: $messages[$id]['proto'] = $proto[1];

                            // Match helo
                            preg_match('/helo=<(.*?)>/', $message, $helo);
                            !isset($helo[1]) ?: $messages[$id]['helo'] = $helo[1];

                            $messages[$id]['host'] = $log->FromHost;
                            $messages[$id]['reported_at'] = $log->DeviceReportedTime;
                        } else {
                            // Try to find the queue id
                            $queueId = $this->getQueueId($message);

                            if (! empty($queueId)) {
                                // Match client
                                preg_match('/client=(.*?)$/', $message, $client);
                                $messages[$queueId]['client'] = isset($client[1]) ? $client[1]: null;

                                // Match encryption for client
                                preg_match('/\[(.*)\]/', $messages[$queueId]['client'], $ip);
                                $messages[$queueId]['encryption'] = optional($this->matchEncryptionIndex(optional($ip)[1], $log->SysLogTag))[0];

                                $messages[$queueId]['host'] = $log->FromHost;
                                $messages[$queueId]['reported_at'] = $log->DeviceReportedTime;
                                $messages[$queueId]['queue_id'] = $queueId;
                            }
                        }
                        break;
                    }
                    case 'cleanup': {
                        $message = trim($log->Message);
                        $queueId = $this->getQueueId($message);

                        if (!empty($queueId)) {
                            preg_match('/]:\s[0-9]\.[0-9]\.[0-9]\s[a-zA-Z]*,\s[a-zA-Z]*=[0-9]*-[0-9]*\s-\s(.*);/', $message, $response);
                            !isset($response[1]) ?: $messages[$queueId]['response'] = $response[1];

                            // Match from
                            preg_match('/from=<(.*?)>/', $message, $from);
                            !isset($from[1]) ?: $messages[$queueId]['from'] = $from[1];

                            // Match to
                            preg_match('/to=<(.*?)>/', $message, $to);
                            !isset($to[1]) ?: $messages[$queueId]['to'] = $to[1];

                            // Match proto
                            preg_match('/proto=(.*?)\s/', $message, $proto);
                            !isset($proto[1]) ?: $messages[$queueId]['proto'] = $proto[1];

                            // Match helo
                            preg_match('/helo=<(.*?)>/', $message, $helo);
                            !isset($helo[1]) ?: $messages[$queueId]['helo'] = $helo[1];

                            // Match client
                            preg_match('/:\sEND-OF-MESSAGE\sfrom\s(.*?):/', $message, $client);
                            !isset($client[1]) ?: $messages[$queueId]['client'] = $client[1];

                            // Match encryption for client
                            preg_match('/\[(.*)\]/', $messages[$queueId]['client'], $ip);
                            $messages[$queueId]['encryption'] = optional($this->matchEncryptionIndex(optional($ip)[1], $log->SysLogTag))[0];

                            // Match dsn
                            preg_match('/]:\s(.*?)\s/', $message, $dsn);
                            !isset($dsn[1]) ?: $messages[$queueId]['dsn'] = $dsn[1];

                            // Match status
                            preg_match('/]:\s[0-9]\.[0-9]\.[0-9]\s(.*?),\s/', $message, $status);
                            !isset($status[1]) ?: $messages[$queueId]['status'] = strtolower($status[1]);

                            $messages[$queueId]['host'] = $log->FromHost;
                            $messages[$queueId]['queue_id'] = $queueId;
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
            return $data['process'] == $syslogTag && $data['ip'] == $ip;
        });
    }

    protected function getQueueId($message)
    {
        preg_match('/^\s([A-Z0-9]+):\s/', $message, $queueId);

        if (isset($queueId[1])) {
            return $queueId[1];
        }

        return null;
    }
}

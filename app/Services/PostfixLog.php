<?php

namespace App\Services;

use Illuminate\Support\Str;

class PostfixLog
{
    public function parse($logs)
    {
        $messages = [];
        foreach($logs as $log) {
            // Match daaemon
            preg_match('/postfix\/([\w]+)/', $log->SysLogTag, $daemon);

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

                            preg_match('/\s\((.*)\)/', $message, $response);
                            !isset($response[1]) ?: $messages[$queueId]['response'] = $response[1];

                            $messages[$queueId]['host'] = $log->FromHost;
                            $messages[$queueId]['queue_id'] = $queueId;
                        }
                        break;
                    }
                    case 'smtpd': {
                        $message = $log->Message;

                        if (Str::startsWith($message,'NOQUEUE:')) {
                            $id = strtoupper(uniqid());

                            // Match status
                            preg_match('/\s([a-z]+):\s/', $message, $status);
                            !isset($status[1]) ?: $messages[$id]['status'] = $status[1];

                            // Match client
                            preg_match('/:\sRCPT\sfrom\s(.*?):/', $message, $client);
                            !isset($client[1]) ?: $messages[$id]['client'] = $client[1];


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

                                $messages[$queueId]['host'] = $log->FromHost;
                                $messages[$queueId]['reported_at'] = $log->DeviceReportedTime;
                                $messages[$queueId]['queue_id'] = $queueId;
                            }
                        }
                        break;
                    }
                    case 'cleanup': {
                        $message = $log->Message;
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

    protected function getQueueId($message)
    {
        preg_match('/^\s([A-Z0-9]+):\s/', $message, $queueId);

        if (isset($queueId[1])) {
            return $queueId[1];
        }

        return null;
    }
}
<?php

namespace App\Services\PostfixPolicyInstallation;

use App\Exceptions\ErrorException;
use App\Services\Milter\Models\MilterException;
use App\Support\IPv4;

class MilterExceptionHandler extends AbstractHandler
{
    public function install()
    {
        $this->task->update(['message' => 'Milter Ausnahmen werden aktualisiert...']);

        $this->insert($this->getMilterExceptionRows());
    }

    public function table()
    {
        return 'milter_exceptions';
    }

    protected function getMilterExceptionRows()
    {
        return MilterException::where('active', '=', 1)
            ->get()
            ->map(function($exception) {
                $data = [];
                switch ($exception->client_type) {
                    case 'client_ipv4':
                    case 'client_ipv6':
                        if ($exception->milters->isEmpty()) {
                            $data[] = ['payload' => $exception->client_payload, 'definition' => 'DISABLE', 'priority' => $exception->priority];
                        } else {
                            $data[] = ['payload' => $exception->client_payload, 'priority' => $exception->priority, 'definition' => $exception->milters->pluck('definition')->join(', ')];
                        }
                        break;
                    case 'client_ipv4_net':
                        $exceptionIPs = IPv4::cidr2range($exception->client_payload);

                        foreach ($exceptionIPs as $index => $ip) {
                            if ($exception->milters->isEmpty()) {
                                $data[] = ['payload' => $ip, 'definition' => 'DISABLE', 'priority' => $exception->priority];
                            } else {
                                foreach ($exception->milters as $milter) {
                                    $data[] = ['payload' => $ip, 'definition' => $milter->definition, 'priority' => $exception->priority];
                                }
                            }
                        }
                        break;
                    default:
                        throw new ErrorException;
                }

                return $data;
            })->flatten(1)
            ->toArray();
    }
}

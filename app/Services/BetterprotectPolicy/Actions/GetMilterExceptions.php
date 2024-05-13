<?php

namespace App\Services\BetterprotectPolicy\Actions;

use App\Exceptions\ErrorException;
use App\Services\BetterprotectPolicy\Repositories\MilterExceptionRepository;
use App\Services\Helpers\IPv4;

class GetMilterExceptions
{
    public function __construct(protected MilterExceptionRepository $milterExceptionRepository) {}

    public function execute()
    {
        return $this->milterExceptionRepository->get()->map(function ($exception) {
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
        })->flatten(1)->toArray();
    }
}

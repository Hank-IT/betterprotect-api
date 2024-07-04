<?php

namespace App\Services\Rules\Actions;

use App\Services\Helpers\Actions\IsValidIpv4Net;
use Illuminate\Validation\ValidationException;

class ValidateClient
{
    public function __construct(protected IsValidIpv4Net $isValidIpv4Net) {}

    public function execute(string $clientType, string $clientPayload): void
    {
        switch ($clientType) {
            case 'client_ipv4':
                if (filter_var($clientPayload, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                    throw ValidationException::withMessages([
                        'client_payload' => 'Muss eine gültige IPv4 Adresse sein.'
                    ]);
                }
                break;
            case 'client_ipv6':
                if (filter_var($clientPayload, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                    throw ValidationException::withMessages([
                        'client_payload' => 'Muss eine gültige IPv6 Adresse sein.'
                    ]);
                }
                break;
            case 'client_ipv4_net':
                if (! $this->isValidIpv4Net->execute($clientPayload)) {
                    throw ValidationException::withMessages([
                        'client_payload' => 'Muss ein gültiges IPv4 Netz sein.'
                    ]);
                }

                break;
        }
    }
}

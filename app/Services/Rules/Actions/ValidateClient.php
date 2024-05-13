<?php

namespace App\Services\Rules\Actions;

use App\Services\Helpers\IPv4;
use Illuminate\Validation\ValidationException;

class ValidateClient
{
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
                if (! IPv4::isValidIPv4Net($clientPayload)) {
                    throw ValidationException::withMessages([
                        'client_payload' => 'Muss ein gültiges IPv4 Netz sein.'
                    ]);
                }

                break;
        }
    }
}

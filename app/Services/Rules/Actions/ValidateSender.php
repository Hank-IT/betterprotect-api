<?php

namespace App\Services\Rules\Actions;

use App\Support\IPv4;
use Illuminate\Validation\ValidationException;

class ValidateSender
{
    public function execute(string $senderType, string $senderPayload): void
    {
        switch ($senderType) {
            case 'mail_from_address':
                if (filter_var($senderPayload, FILTER_VALIDATE_EMAIL) === false) {
                    throw ValidationException::withMessages([
                        'sender_payload' => 'Muss eine gültige E-Mail Adresse sein.'
                    ]);
                }
                break;
        };
    }
}

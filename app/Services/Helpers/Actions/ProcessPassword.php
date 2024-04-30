<?php

namespace App\Services\Helpers\Actions;

class ProcessPassword
{
    public function execute(?string $password, ?string $default): ?string
    {
        // Empty string means we clear the password
        if ($password === '') {

            return '';
        }

        // Null means we use the provided default
        if(is_null($password)) {
            return $default;
        }

        return $password;
    }
}

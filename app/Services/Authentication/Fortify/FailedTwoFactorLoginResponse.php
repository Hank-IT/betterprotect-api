<?php

namespace App\Services\Authentication\Fortify;

use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;

class FailedTwoFactorLoginResponse implements FailedTwoFactorLoginResponseContract
{
    public function toResponse($request)
    {
        throw ValidationException::withMessages([
            'token' => 'Der eingegeben Code ist falsch.',
        ]);
    }
}

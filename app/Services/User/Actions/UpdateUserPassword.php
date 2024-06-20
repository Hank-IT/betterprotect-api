<?php

namespace App\Services\User\Actions;

use App\Services\Authentication\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserPassword
{
    public function execute(User $user, string $password): void
    {
        $user->update([
            'password' => Hash::make($password),
        ]);
    }
}

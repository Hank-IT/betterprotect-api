<?php

namespace App\Services\User\Actions;

use App\Services\Authentication\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    public function execute(
        string $username,
        string $password,
        string $role,
        ?string $email = null,
    ): User {
        return User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'role' => $role,
            'email' => $email,
        ]);
    }
}

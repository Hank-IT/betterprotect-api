<?php

namespace App\Services\User\Actions;

use App\Services\Authentication\Models\User;

class UpdateUser
{
    public function execute(User $user, string $role, ?string $email): void
    {
        $user->update([
            'role' => $role,
            'email' => $email,
        ]);
    }
}

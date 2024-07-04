<?php

namespace App\Services\User\Actions;

use App\Services\Authentication\Models\User;

class UpdateUserRole
{
    public function execute(User $user, string $role): void
    {
        $user->update([
            'role' => $role,
        ]);
    }
}

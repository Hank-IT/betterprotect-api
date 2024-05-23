<?php

namespace App\Services\User\Actions;

use App\Services\Authentication\Models\User;

class DeleteUser
{
    public function execute(User $user): ?bool
    {
        return $user->delete();
    }
}

<?php

namespace App\Services\Authentication;

use App\Exceptions\ErrorException;
use App\Services\Authentication\Models\User;

class Authorizer
{
    protected User $user;

    protected array $roles = [
        'readonly' => [
            'priority' => 10,
        ],
        'authorizer' => [
            'priority' => 20,
        ],
        'editor' => [
            'priority' => 30,
        ],
        'administrator' => [
            'priority' => 40,
        ],
    ];

    public function __construct(User $user)
    {
        if (! $user->exists) {
            throw new ErrorException('Der angegebene Benutzer existiert nicht.');
        }

        $this->user = $user;
    }

    public function isRole(string $role): bool
    {
        return $this->getUserRolePriority() >= $this->getTargetRolePriority($role);
    }

    protected function getUserRolePriority(): int
    {
        if (! isset($this->roles[$this->user->role])) {
            throw new ErrorException('Die angegebene Rolle existiert nicht.');
        }

        return $this->roles[$this->user->role]['priority'];
    }

    protected function getTargetRolePriority(string $role): int
    {
        if (! isset($this->roles[$role])) {
            throw new ErrorException('Die angegebene Rolle existiert nicht.');
        }

        return $this->roles[$role]['priority'];
    }
}

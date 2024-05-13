<?php

namespace App\Services\Authentication;

use App\Exceptions\ErrorException;
use App\Services\Authentication\Models\User;

class Authorizer
{
    /**
     * @var User
     */
    protected $user;

    /**
     * Available roles with priority
     *
     * @var array
     */
    protected $roles = [
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

    /**
     * Authorizer constructor.
     * @param User $user
     * @throws ErrorException
     */
    public function __construct(User $user)
    {
        if (! $user->exists) {
            throw new ErrorException('Der angegebene Benutzer existiert nicht.');
        }

        $this->user = $user;
    }

    /**
     * @param string $role
     * @return bool
     * @throws ErrorException
     */
    public function isRole(string $role)
    {
        return $this->getUserRolePriority() >= $this->getTargetRolePriority($role);
    }

    /**
     * @return mixed
     * @throws ErrorException
     */
    protected function getUserRolePriority()
    {
        if (! isset($this->roles[$this->user->role])) {
            throw new ErrorException('Die angegebene Rolle existiert nicht.');
        }

        return $this->roles[$this->user->role]['priority'];
    }

    /**
     * @param string $role
     * @return mixed
     * @throws ErrorException
     */
    protected function getTargetRolePriority(string $role)
    {
        if (! isset($this->roles[$role])) {
            throw new ErrorException('Die angegebene Rolle existiert nicht.');
        }

        return $this->roles[$role]['priority'];
    }
}

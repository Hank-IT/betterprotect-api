<?php

namespace App\Services\Authentication\Ldap\Rules;

use LdapRecord\Models\ActiveDirectory\Group;
use Illuminate\Database\Eloquent\Model as Eloquent;
use LdapRecord\Laravel\Auth\Rule;
use LdapRecord\Models\Model as LdapRecord;

class OnlyConfiguredGroupMembers implements Rule
{
    /**
     * Check if the rule passes validation.
     */
    public function passes(LdapRecord $user, Eloquent $model = null): bool
    {
        return $user->groups()->exists(
            Group::findByGuid(config('auth.ldap_login_group'))
        );
    }
}

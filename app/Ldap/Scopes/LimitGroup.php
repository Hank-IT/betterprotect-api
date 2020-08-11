<?php

namespace App\Ldap\Scopes;

use App\Support\Util;
use Adldap\Query\Builder;
use Adldap\Laravel\Scopes\ScopeInterface;

class LimitGroup implements ScopeInterface
{
    public function apply(Builder $query)
    {
        $ldapDirectory = Util::activeLdapAuthDirectory();

        if (optional($ldapDirectory)->group_dn) {
            $query->whereEquals('memberof', $ldapDirectory->group_dn);
        }
    }
}
<?php

namespace App\Services\Recipients\Actions;

use Illuminate\Support\Str;

class GetIgnoredLdapDomains
{
    public function execute(): array
    {
        $configuredDomains = config('betterprotect.ldap_query_ignored_domains');

        return is_null($configuredDomains)
            ? []
            : explode(',', Str::replace(' ', '', $configuredDomains));
    }
}

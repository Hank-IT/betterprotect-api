<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Models\LdapDirectory;
use App\Services\Recipients\Jobs\RefreshLdapRecipients;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RecipientLdapController extends Controller
{
    public function __invoke(LdapDirectory $ldapDirectory)
    {
        $configuredDomains = config('betterprotect.ldap_query_ignored_domains');

        $domains = is_null($configuredDomains)
            ? []
            : explode(',', Str::replace(' ', '', $configuredDomains));

        RefreshLdapRecipients::dispatch(
            (string) Str::uuid(),
            'ldap',
            Auth::user()->username,
            $domains,
        );

        return response(status: Response::HTTP_ACCEPTED);
    }
}

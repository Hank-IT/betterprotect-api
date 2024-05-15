<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Services\Recipients\Actions\GetIgnoredLdapDomains;
use App\Services\Recipients\Jobs\RefreshLdapRecipients;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RecipientLdapController extends Controller
{
    public function __invoke(GetIgnoredLdapDomains $getIgnoredLdapDomains)
    {
        RefreshLdapRecipients::dispatch(
            (string) Str::uuid(),
            'ldap',
            Auth::user()->username,
            $getIgnoredLdapDomains->execute(),
        );

        return response(status: Response::HTTP_ACCEPTED);
    }
}

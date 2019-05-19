<?php

namespace App\Http\Controllers;

use App\Models\LdapDirectory;
use App\Services\LdapRecipientQuery;
use Symfony\Component\HttpFoundation\Response;

class RecipientLdapController extends Controller
{
    public function __invoke(LdapDirectory $ldapDirectory)
    {
        LdapRecipientQuery::run($ldapDirectory);

        return response()->json([
            'status' => 'success',
            'message' => 'Aufgabe wurde eingereiht.',
            'data' => [],
        ], Response::HTTP_ACCEPTED);
    }
}

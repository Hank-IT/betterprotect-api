<?php

namespace App\Services;

use App\Models\LdapDirectory;
use App\Jobs\QueryLdapRecipients;
use Illuminate\Support\Facades\Auth;

class LdapRecipientQuery
{
    public static function run(LdapDirectory $ldapDirectory)
    {
        $viewTask = app(ViewTask::class, [
            'message' => 'LDAP ' . $ldapDirectory->connection . ': Abfrage wird durchgeführt.',
            'task' => 'query-ldap-recipients',
            'username' => Auth::user()->username,
        ]);

        // Pull all recipients from active directory
        $users = collect($ldapDirectory->getLdapConnection()->search()->users()->paginate()->getResults());
        $groups = collect($ldapDirectory->getLdapConnection()->search()->groups()->paginate()->getResults());

        // Loop through all recipients and get the smtp addresses
        $userRecipients = $users->map->getProxyAddresses();
        $groupRecipients = $groups->map(function($group) {
            return $group->getAttribute($group->getSchema()->proxyAddresses());
        });

        $recipients = $userRecipients->merge($groupRecipients);

        // Remove null values, reorder and flatten
        $addresses = $recipients->filter()->values()->flatten();

        QueryLdapRecipients::dispatch(base64_encode(serialize($addresses)), $viewTask, $ldapDirectory->connection, $ldapDirectory->ignored_domains)->onQueue('task');
    }
}
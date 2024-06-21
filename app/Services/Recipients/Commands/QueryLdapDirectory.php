<?php

namespace App\Services\Recipients\Commands;

use App\Services\Recipients\Actions\GetIgnoredLdapDomains;
use App\Services\Recipients\Jobs\RefreshLdapRecipients;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class QueryLdapDirectory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ldap:query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Query the ldap directory for recipients.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(GetIgnoredLdapDomains $getIgnoredLdapDomains)
    {
        RefreshLdapRecipients::dispatch(
            (string) Str::uuid(),
            'ldap',
            'System',
            $getIgnoredLdapDomains->execute(),
        )->onQueue('task');

        return 0;
    }
}

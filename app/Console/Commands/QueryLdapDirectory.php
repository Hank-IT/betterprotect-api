<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\LdapDirectory;
use Illuminate\Console\Command;
use App\Services\LdapRecipientQuery;

class QueryLdapDirectory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ldap:query {connection}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Query the specified ldap directory for recipients.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $connection = $this->argument('connection');

        if (! $connectionModel = LdapDirectory::where('connection', '=', $connection)->first()) {
            $this->error('The provided connection does not exist.');

            return false;
        }

        LdapRecipientQuery::run($connectionModel, new User(['username' => 'System']));

        return true;
    }
}

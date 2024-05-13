<?php

namespace App\Services\Recipients\Commands;

use App\Models\LdapDirectory;
use App\Services\Authentication\Models\User;
use App\Services\LdapRecipientQuery;
use Illuminate\Console\Command;

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

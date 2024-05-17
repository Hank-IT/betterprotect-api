<?php

namespace App\Services\BetterprotectPolicy\Commands;

use App\Services\BetterprotectPolicy\Jobs\BetterprotectPolicyInstallation;
use App\Services\Server\Models\Server;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallPolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'policy:install {server : The target server\'s hostname or id. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the policy on the specified server.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $server = $this->argument('server');

        if (! $serverModel = Server::find($server)) {
            if (! $serverModel = Server::where('hostname', '=', $server)->first()) {
                $this->error('The provided server does not exist.');

                return 1;
            }
        }

        BetterprotectPolicyInstallation::dispatchSync($serverModel, (string) Str::uuid(), 'System');

        return 0;
    }
}
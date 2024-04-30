<?php

namespace Tests\Feature\Services\Server\Actions;

use App\Services\Server\Actions\CreateServer;
use App\Services\Server\dtos\DatabaseDetails;
use App\Services\Server\Models\Server;
use Tests\TestCase;

class CreateServerTest extends TestCase
{
    public function test()
    {
        $action = app(CreateServer::class);

        $postfixDb = DatabaseDetails::factory()->make();

        $logDb = DatabaseDetails::factory()->make();

        $props = Server::factory()->make();

        $server = $action->execute(
            $props->hostname,
            $postfixDb,
            $logDb,
            $props->ssh_user,
            $props->ssh_public_key,
            $props->ssh_private_key,
            $props->ssh_command_sudo,
            $props->ssh_command_postqueue,
            $props->ssh_command_postsuper,
        );

        $this->assertModelExists($server);

        $this->assertDatabaseHas('servers', [
            'hostname' =>    $props['hostname'],

            'postfix_db_host' => $postfixDb->getHostname(),
            'postfix_db_name' => $postfixDb->getDatabase(),
            'postfix_db_user' => $postfixDb->getUsername(),
            'postfix_db_port' => $postfixDb->getPort(),

            'log_db_host' => $logDb->getHostname(),
            'log_db_name' => $logDb->getDatabase(),
            'log_db_user' => $logDb->getUsername(),
            'log_db_port' => $logDb->getPort(),

            'ssh_user' => $props->ssh_user,
            'ssh_public_key' => $props->ssh_public_key,
            'ssh_command_sudo' => $props->ssh_command_sudo,
            'ssh_command_postqueue' => $props->ssh_command_postqueue,
            'ssh_command_postsuper' => $props->ssh_command_postsuper,
        ]);

        $this->assertDatabaseMissing('servers', [
            'id' => $server->getKey(),
            'log_db_password' => $logDb->getPassword(),
            'ssh_private_key' => $props['ssh_private_key'],
            'postfix_db_password' => $postfixDb->getPassword(),
        ]);
    }
}

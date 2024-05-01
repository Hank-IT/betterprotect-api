<?php

namespace Tests\Feature\Controllers\API\Server;

use App\Services\Authentication\Models\User;
use App\Services\Server\Actions\CreateServer;
use App\Services\Server\Actions\DeleteServer;
use App\Services\Server\Actions\UpdateServer;
use App\Services\Server\dtos\DatabaseDetails;
use App\Services\Server\Models\Server;
use Mockery\MockInterface;
use Tests\TestCase;

class ServerControllerTest extends TestCase
{
    public function testIndex()
    {
        $user = User::factory()->create();

        $this->be($user);

         Server::factory()->count(2)->create();

        $this->getJson(route('api.v1.server.index'))->assertSuccessful();
    }

    public function testShow()
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $this->getJson(route('api.v1.server.show', $server->getKey()))->assertSuccessful()->assertjsonPath('data.id', $server->getKey());
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $this->be($user);

        $this->mock(CreateServer::class, function(MockInterface $mock) {
            $mock->shouldReceive('execute');
        });

        $postfixDb = DatabaseDetails::factory()->make();

        $logDb = DatabaseDetails::factory()->make();

        $props = Server::factory()->make();

        $this->postJson(route('api.v1.server.store'), [
            'hostname' =>    $props['hostname'],

            'postfix_db_host' => $postfixDb->getHostname(),
            'postfix_db_name' => $postfixDb->getDatabase(),
            'postfix_db_user' => $postfixDb->getUsername(),
            'postfix_db_port' => $postfixDb->getPort(),
            'postfix_db_password' => fake()->password,

            'log_db_host' => $logDb->getHostname(),
            'log_db_name' => $logDb->getDatabase(),
            'log_db_user' => $logDb->getUsername(),
            'log_db_port' => $logDb->getPort(),
            'log_db_password' => fake()->password,

            'ssh_user' => $props->ssh_user,
            'ssh_public_key' => $props->ssh_public_key,
            'ssh_private_key' => fake()->text,
            'ssh_command_sudo' => $props->ssh_command_sudo,
            'ssh_command_postqueue' => $props->ssh_command_postqueue,
            'ssh_command_postsuper' => $props->ssh_command_postsuper,
        ])->assertSuccessful();
    }

    public function testUpdate()
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $this->mock(UpdateServer::class, function(MockInterface $mock) {
            $mock->shouldReceive('execute');
        });

        $postfixDb = DatabaseDetails::factory()->make();

        $logDb = DatabaseDetails::factory()->make();

        $props = Server::factory()->make();

        $this->putJson(route('api.v1.server.update', $server->getKey()), [
            'hostname' =>    $props['hostname'],

            'postfix_db_host' => $postfixDb->getHostname(),
            'postfix_db_name' => $postfixDb->getDatabase(),
            'postfix_db_user' => $postfixDb->getUsername(),
            'postfix_db_port' => $postfixDb->getPort(),
            'postfix_db_password' => fake()->password,

            'log_db_host' => $logDb->getHostname(),
            'log_db_name' => $logDb->getDatabase(),
            'log_db_user' => $logDb->getUsername(),
            'log_db_port' => $logDb->getPort(),
            'log_db_password' => fake()->password,

            'ssh_user' => $props->ssh_user,
            'ssh_public_key' => $props->ssh_public_key,
            'ssh_private_key' => fake()->text,
            'ssh_command_sudo' => $props->ssh_command_sudo,
            'ssh_command_postqueue' => $props->ssh_command_postqueue,
            'ssh_command_postsuper' => $props->ssh_command_postsuper,
        ])->assertSuccessful();
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $this->mock(DeleteServer::class, function(MockInterface $mock) {
            $mock->shouldReceive('execute');
        });

        $this->deleteJson(route('api.v1.server.destroy', $server->getKey()))->assertSuccessful();
    }
}

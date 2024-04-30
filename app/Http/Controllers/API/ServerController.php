<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Helpers\Actions\ProcessPassword;
use App\Services\Server\Actions\CreateServer;
use App\Services\Server\Actions\DeleteServer;
use App\Services\Server\Actions\UpdateServer;
use App\Services\Server\dtos\DatabaseDetails;
use App\Services\Server\Models\Server;
use App\Services\Server\Resources\ServerIndexResource;
use App\Services\Server\Resources\ServerResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServerController extends Controller
{
    public function index()
    {
        return ServerIndexResource::collection(Server::all());
    }

    public function show(Server $server)
    {
        return new ServerResource($server);
    }

    public function store(Request $request, CreateServer $createServer)
    {
        $data = $request->validate([
            'hostname' => ['required', 'string', Rule::unique('servers')],

            'postfix_db_host' => ['required', 'string'],
            'postfix_db_name' => ['required', 'string'],
            'postfix_db_user' => ['nullable', 'string'],
            'postfix_db_password' => ['nullable', 'string'],
            'postfix_db_port' => ['required', 'integer'],

            'ssh_user' => ['required', 'string'],
            'ssh_public_key' => ['required', 'string'],
            'ssh_private_key' => ['required', 'string'],
            'ssh_command_sudo' => ['required', 'string'],
            'ssh_command_postqueue' => ['required', 'string'],
            'ssh_command_postsuper' => ['required', 'string'],

            'log_db_host' => ['required', 'string'],
            'log_db_name' => ['required', 'string'],
            'log_db_user' => ['nullable', 'string'],
            'log_db_password' => ['nullable', 'string'],
            'log_db_port' => ['required', 'integer'],
        ]);

        $postfixDb = DatabaseDetails::make(
            $data['postfix_db_host'],
            $data['postfix_db_name'],
            $data['postfix_db_user'],
            $data['postfix_db_password'],
            $data['postfix_db_port'],
        );

        $logDb = DatabaseDetails::make(
            $data['log_db_host'],
            $data['log_db_name'],
            $data['log_db_user'],
            $data['log_db_password'],
            $data['log_db_port'],
        );

        $createServer->execute(
            $data['hostname'],
            $postfixDb,
            $logDb,
            $data['ssh_user'],
            $data['ssh_public_key'],
            $data['ssh_private_key'],
            $data['ssh_command_sudo'],
            $data['ssh_command_postqueue'],
            $data['ssh_command_postsuper'],
        );

        return response(status: 201);
    }

    public function update(Request $request, ProcessPassword $processPassword, UpdateServer $updateServer, Server $server)
    {
        $data = $request->validate([
            'hostname' => ['required', 'string', Rule::unique('servers')->ignoreModel($server)],

            'postfix_db_host' => ['required', 'string'],
            'postfix_db_name' => ['required', 'string'],
            'postfix_db_user' => ['nullable', 'string'],
            'postfix_db_password' => ['nullable', 'string'],
            'postfix_db_port' => ['required', 'integer'],

            'ssh_user' => ['required', 'string'],
            'ssh_public_key' => ['required', 'string'],
            'ssh_private_key' => ['required', 'string'],
            'ssh_command_sudo' => ['required', 'string'],
            'ssh_command_postqueue' => ['required', 'string'],
            'ssh_command_postsuper' => ['required', 'string'],

            'log_db_host' => ['required', 'string'],
            'log_db_name' => ['required', 'string'],
            'log_db_user' => ['nullable', 'string'],
            'log_db_password' => ['nullable', 'string'],
            'log_db_port' => ['required', 'integer'],
        ]);

        $postfixDb = DatabaseDetails::make(
            $data['postfix_db_host'],
            $data['postfix_db_name'],
            $data['postfix_db_user'],
            $processPassword->execute($data['postfix_db_password'], $server->postfix_db_password),
            $data['postfix_db_port'],
        );

        $logDb = DatabaseDetails::make(
            $data['log_db_host'],
            $data['log_db_name'],
            $data['log_db_user'],
            $processPassword->execute($data['log_db_password'], $server->log_db_password),
            $data['log_db_port'],
        );

        $updateServer->execute(
            $server,
            $data['hostname'],
            $postfixDb,
            $logDb,
            $data['ssh_user'],
            $data['ssh_public_key'],
            $processPassword->execute($data['ssh_private_key'], $server->ssh_private_key),
            $data['ssh_command_sudo'],
            $data['ssh_command_postqueue'],
            $data['ssh_command_postsuper'],
        );

        return response(status: 200);
    }

    public function destroy(Server $server, DeleteServer $deleteServer)
    {
        $deleteServer->execute($server);

        return response(status: 200);
    }
}

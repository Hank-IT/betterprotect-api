<?php

namespace App\Services\Server\Actions;

use App\Services\Server\dtos\DatabaseDetails;
use App\Services\Server\Models\Server;

class UpdateServer
{
    public function execute(
        Server $server,
        string $hostname,
        DatabaseDetails $postfixDb,
        DatabaseDetails $logDb,
        string $sshUser,
        string $publicKey,
        string $privateKey,
        string $sudo,
        string $postqueue,
        string $postsuper,
    ): void {
        $server->update([
            'hostname' => $hostname,

            'postfix_db_host' => $postfixDb->getHostname(),
            'postfix_db_name' => $postfixDb->getDatabase(),
            'postfix_db_user' => $postfixDb->getUsername(),
            'postfix_db_port' => $postfixDb->getPort(),
            'postfix_db_password' => encrypt($postfixDb->getPassword()),

            'log_db_host' => $logDb->getHostname(),
            'log_db_name' => $logDb->getDatabase(),
            'log_db_user' => $logDb->getUsername(),
            'log_db_port' => $logDb->getPort(),
            'log_db_password' => encrypt($logDb->getPassword()),

            'ssh_user' => $sshUser,
            'ssh_public_key' => $publicKey,
            'ssh_private_key' => encrypt($privateKey),
            'ssh_command_sudo' => $sudo,
            'ssh_command_postqueue' => $postqueue,
            'ssh_command_postsuper' => $postsuper,
        ]);
    }
}

<?php

namespace App\Services\Server\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServerFullResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->getKey(),
            'hostname' => $this->hostname,
            'postfix_db_host' => $this->postfix_db_host,
            'postfix_db_name' => $this->postfix_db_name,
            'postfix_db_user' => $this->postfix_db_user,
            'postfix_db_port' => $this->postfix_db_port,
            'ssh_user' => $this->ssh_user,
            'ssh_public_key' => $this->ssh_public_key,
            'ssh_command_sudo' => $this->ssh_command_sudo,
            'ssh_command_postqueue' => $this->ssh_command_postqueue,
            'ssh_command_postsuper' => $this->ssh_command_postsuper,
            'log_db_host' => $this->log_db_host,
            'log_db_name' => $this->log_db_name,
            'log_db_user' => $this->log_db_user,
            'log_db_port' => $this->log_db_port,
        ];
    }
}

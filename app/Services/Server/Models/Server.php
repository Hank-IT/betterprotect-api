<?php

namespace App\Services\Server\Models;

use App\Services\Server\dtos\DatabaseDetails;
use App\Services\Server\dtos\SSHDetails;
use Database\Factories\ServerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['postfix_db_password', 'log_db_password', 'ssh_private_key'];

    protected $casts = [
        'last_policy_install' => 'datetime',
    ];

    public function getDatabaseDetails(string $database): DatabaseDetails
    {
        return new DatabaseDetails([
            'hostname' => $this->{"{$database}_db_host"},
            'database' => $this->{"{$database}_db_name"},
            'username' => $this->{"{$database}_db_user"},
            'password' => decrypt($this->{"{$database}_db_password"}),
            'port' => $this->{"{$database}_db_port"},
        ]);
    }

    public function getSSHDetails(): SSHDetails
    {
        return new SSHDetails([
            'hostname' => $this->hostname,
            'user' => $this->ssh_user,
            'public_key' => $this->ssh_public_key,
            'private_Key' => decrypt($this->ssh_private_key),
            'postqueue_command' => $this->ssh_command_postqueue,
            'postsuper_command' => $this->ssh_command_postsuper,
            'sudo_command' => $this->ssh_command_sudo,
        ]);
    }

    protected static function newFactory()
    {
        return ServerFactory::new();
    }
}

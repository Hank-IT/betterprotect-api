<?php

namespace App\Services\Server\Models;

use App\Services\Server\dtos\DatabaseDetails;
use App\Services\Server\ServerConsole;
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

    /**
     * @throws PublicKeyMismatchException
     * @throws \MrCrankHank\ConsoleAccess\Exceptions\ConnectionNotPossibleException
     */
    public function console()
    {
        return app(ServerConsole::class, ['server' => $this]);
    }

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

    public function getSshPrivateKeyAttribute()
    {
        return decrypt($this->attributes['ssh_private_key']);
    }

    protected static function newFactory()
    {
        return ServerFactory::new();
    }
}

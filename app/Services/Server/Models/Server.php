<?php

namespace App\Services\Server\Models;

use App\Services\Server\dtos\DatabaseDetails;
use App\Services\Server\Filesystem;
use App\Services\Server\ServerConsole;
use Database\Factories\ServerFactory;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException;

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

    public function postfixDatabaseDetails(): DatabaseDetails
    {
        return new DatabaseDetails([
            'hostname' => $this->postfix_db_host,
            'database' => $this->postfix_db_name,
            'username' => $this->postfix_db_user,
            'password' => decrypt($this->postfix_db_password),
            'port' => $this->postfix_db_port,
        ]);
    }

    public function logDatabaseDetails(): DatabaseDetails
    {
        return new DatabaseDetails([
            'hostname' => $this->log_db_host,
            'database' => $this->log_db_name,
            'username' => $this->log_db_user,
            'password' => decrypt($this->log_db_password),
            'port' => $this->log_db_port,
        ]);
    }

    public function filesystem(): FilesystemContract
    {
        return app(Filesystem::class)->getFilesystem();
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

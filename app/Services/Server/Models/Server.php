<?php

namespace App\Services\Server\Models;

use App\Concerns\SerializesDate;
use App\Services\Filesystem;
use App\Services\Server\Database\LogDatabase;
use App\Services\Server\Database\PostfixDatabase;
use App\Services\ServerConsole;
use Database\Factories\ServerFactory;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException;

class Server extends Model
{
    use SerializesDate, HasFactory;

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

    public function postfixDatabase()
    {
        return app(PostfixDatabase::class, ['server' => $this]);
    }

    public function logDatabase()
    {
        return app(LogDatabase::class, ['server' => $this]);
    }

    public function filesystem(): FilesystemContract
    {
        return app(Filesystem::class)->getFilesystem();
    }

    public function getPostfixDBPasswordAttribute()
    {
        return decrypt($this->attributes['postfix_db_password']);
    }

    public function getLogDBPasswordAttribute()
    {
        return decrypt($this->attributes['log_db_password']);
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

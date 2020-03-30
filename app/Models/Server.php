<?php

namespace App\Models;

use App\Services\Filesystem;
use App\Services\ServerConsole;
use App\Concerns\SerializesDate;
use App\Services\Database\LogDatabase;
use Illuminate\Database\Eloquent\Model;
use App\Services\Database\AmavisDatabase;
use App\Services\Database\PostfixDatabase;
use MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;

class Server extends Model
{
    use SerializesDate;

    protected $fillable = [
        'hostname',
        'description',

        /**
         * Postfix
         */
        'postfix_db_host',
        'postfix_db_name',
        'postfix_db_user',
        'postfix_db_password',
        'postfix_db_port',
        'postfix_feature_enabled',

        /**
         * Logging
         */
        'log_db_host',
        'log_db_name',
        'log_db_user',
        'log_db_password',
        'log_db_port',
        'log_feature_enabled',

        /**
         * SSH
         */
        'ssh_user',
        'ssh_public_key',
        'ssh_private_key',
        'ssh_command_sudo',
        'ssh_command_postqueue',
        'ssh_command_postsuper',
        'ssh_feature_enabled',

        /**
         * Amavis
         */
        'amavis_db_host',
        'amavis_db_name',
        'amavis_db_user',
        'amavis_db_password',
        'amavis_db_port',
        'amavis_feature_enabled',
    ];

    protected $hidden = ['postfix_db_password', 'log_db_password', 'ssh_private_key', 'amavis_db_password'];

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

    public function amavisDatabase()
    {
        return app(AmavisDatabase::class, ['server' => $this]);
    }

    public function logDatabase()
    {
        return app(LogDatabase::class, ['server' => $this]);
    }

    public function filesystem(): FilesystemContract
    {
        return app(Filesystem::class)->getFilesystem();
    }

    public function setPostfixDBPasswordAttribute($value)
    {
        $this->attributes['postfix_db_password'] = encrypt($value);
    }

    public function getPostfixDBPasswordAttribute()
    {
        return decrypt($this->attributes['postfix_db_password']);
    }

    public function setLogDBPasswordAttribute($value)
    {
        $this->attributes['log_db_password'] = encrypt($value);
    }

    public function getLogDBPasswordAttribute()
    {
        return decrypt($this->attributes['log_db_password']);
    }

    public function setAmavisDBPasswordAttribute($value)
    {
        $this->attributes['amavis_db_password'] = encrypt($value);
    }

    public function getAmavisDBPasswordAttribute()
    {
        return decrypt($this->attributes['amavis_db_password']);
    }

    public function setSshPrivateKeyAttribute($value)
    {
        $this->attributes['ssh_private_key'] = encrypt($value);
    }

    public function getSshPrivateKeyAttribute()
    {
        return decrypt($this->attributes['ssh_private_key']);
    }
}

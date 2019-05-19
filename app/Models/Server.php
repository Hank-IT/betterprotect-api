<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MrCrankHank\ConsoleAccess\Adapters\SshAdapter;
use MrCrankHank\ConsoleAccess\ConsoleAccess;
use MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException;

class Server extends Model
{
    protected $fillable = [
        'hostname',
        'description',
        'db_host',
        'db_name',
        'db_user',
        'db_password',
        'db_port',
    ];

    protected $casts = [
        'binaries' => 'array'
    ];

    protected $hidden = ['db_password', 'private_key'];

    public function getBinariesAttribute($value)
    {
        if (is_null($value)) {
            $value = [];
        }

        return $value;
    }

    /**
     * @throws PublicKeyMismatchException
     */
    public function console()
    {
        $adapter = new SshAdapter($this->hostname, $this->user, $this->public_key);

        $adapter->loginKey($this->private_key);

        return new ConsoleAccess($adapter);
    }

    public function setDBPasswordAttribute($value)
    {
        $this->attributes['db_password'] = encrypt($value);
    }

    public function getDBPasswordAttribute()
    {
        return decrypt($this->attributes['db_password']);
    }

    public function setPrivateKeyAttribute($value)
    {
        $this->attributes['private_key'] = encrypt($value);
    }

    public function getPrivateKeyAttribute()
    {
        return decrypt($this->attributes['private_key']);
    }
}

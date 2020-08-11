<?php

namespace App\Services;

use App\Models\Server;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;

class Filesystem
{
    protected $server;

    public function __construct(Server $server)
    {
        $this->server = $server;

        Config::set('disks.' . $this->server->hostname, $this->config());
    }

    protected function config()
    {
        return [
            'driver' => 'sftp',
            'host' => $this->server->hostname,
            'port' => 22,
            'username' => $this->server->ssh_user,
            'privateKey' => $this->server->ssh_private_key,
            'hostFingerprint' => $this->server->ssh_public_key,
            'timeout' => 10,
        ];
    }

    public function getFilesystem(): FilesystemContract
    {
        return Storage::disk($this->server->hostname);
    }
}

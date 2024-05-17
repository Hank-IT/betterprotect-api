<?php

namespace App\Services\Server\Checks;

use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Models\Server;

class SshConnection implements ServerMonitoringCheck
{
    public function getState(Server $server): mixed
    {
        try {
            // ToDO connect and see if successful
        } catch(ErrorException $exception) {
            // probably connection failed, timeout, etc.
        } catch(PublicKeyMismatchException $exception) {
            // public key mismatch
            //Log::debug($this->server->hostname . ' public key: ' . $console->getPublicKey());
            //throw ValidationException::withMessages(['ssh_public_key' => 'Public Key stimmt nicht überein.']);
        }
    }

    public function getKey(): string
    {
        return 'ssh-connection';
    }
}

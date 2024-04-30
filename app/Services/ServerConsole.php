<?php

namespace App\Services;

use App\Services\Server\Models\Server;
use MrCrankHank\ConsoleAccess\Adapters\SshAdapter;
use MrCrankHank\ConsoleAccess\ConsoleAccess;

class ServerConsole
{
    protected $server;

    protected $output;

    protected $adapter;

    /**
     * ServerConsole constructor.
     * @param Server $server
     * @throws \MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException
     */
    public function __construct(Server $server)
    {
        $this->server = $server;

        $this->adapter = new SshAdapter($this->server->hostname, $this->server->ssh_user, $this->server->ssh_public_key);
    }

    /**
     * @return ConsoleAccess
     * @throws \MrCrankHank\ConsoleAccess\Exceptions\ConnectionNotPossibleException
     */
    public function access()
    {
        $this->adapter->loginKey($this->server->ssh_private_key);

        return new ConsoleAccess($this->adapter);
    }

    /**
     * @return bool
     */
    public function available()
    {
        return $this->adapter->available();
    }

    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->adapter->getServerPublicHostKey();
    }
}

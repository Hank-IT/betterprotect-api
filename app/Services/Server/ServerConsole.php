<?php

namespace App\Services\Server;

use App\Services\Server\Models\Server;
use HankIT\ConsoleAccess\Adapters\SshAdapter\Adapter as SshAdapter;
use HankIT\ConsoleAccess\Interfaces\AdapterInterface;
use MrCrankHank\ConsoleAccess\ConsoleAccess;

class ServerConsole
{
    protected AdapterInterface $adapter;

    public function __construct(protected Server $server)
    {
        // ToDo
        $this->adapter = new SshAdapter(
            $this->server->hostname, $this->server->ssh_user, $this->server->ssh_public_key
        );
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

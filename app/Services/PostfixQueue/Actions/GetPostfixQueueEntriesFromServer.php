<?php

namespace App\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\DataDriver\SshDriver;
use App\Services\PostfixQueue\PostfixQueue;
use App\Services\Server\dtos\SSHDetails;

class GetPostfixQueueEntriesFromServer
{
    public function execute(SSHDetails $sshDetails): array
    {
        return (new PostfixQueue(new SshDriver($sshDetails)))->get();
    }
}

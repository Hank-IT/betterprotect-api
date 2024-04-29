<?php

declare(strict_types=1);

namespace App\Services\MailLogging\LegacyPostfixParser\Parser\Daemons;

class Qmgr extends Daemon
{
    protected function getRegex(): string
    {
        return '/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): ?from=<?(?<from>[^>,]*)>?, size=(?<size>[0-9]+), nrcpt=(?<nrcpt>[0-9]+)/';
    }
}

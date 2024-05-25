<?php

declare(strict_types=1);

namespace App\Services\PostfixLog\LegacyPostfixParser\Parser\Daemons;

class Smtp extends Daemon
{
    protected function getRegex(): string
    {
        return '/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): ?to=<?(?<to>[^>,]*)>?, (?:orig_to=<?([^>,]*)>?, )?relay=(?<relay>[^,]*\[(?<relay_ip>.*)\]:[0-9]+), (?:conn_use=([0-9]+), )?delay=(?<delay>[^,]+), delays=(?<delays>[^,]+), dsn=(?<dsn>[^,]+), status=(?<status>.*?) \((?<response>.*)\)$/';
    }
}

<?php

declare(strict_types=1);

namespace App\Postfix\Parser\Daemons;

use Illuminate\Support\Str;

class Cleanup extends Daemon
{
    protected function getRegex(): string
    {
        return Str::contains($this->payload, 'warning: header Subject')
            ? '/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): warning: header subject: (?<subject>.*?)from/i'
            : '/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): .*: END-OF-MESSAGE from (?<client>.*): (?<response>[0-9]\.[0-9]\.[0-9] (?<status>.*?), .*) from=<?(?<from>[^>,]*)>? to=<?(?<to>[^>,]*)>? proto=(?<proto>.*?) helo=<?(?<helo>[^>,]*)/';
    }
}

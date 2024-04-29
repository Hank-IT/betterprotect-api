<?php

declare(strict_types=1);

namespace App\Services\MailLogging\LegacyPostfixParser\Parser\Daemons;

use Illuminate\Support\Str;

class Smtpd extends Daemon
{
    protected $payload;

    protected function getRegex(): string
    {
        return Str::startsWith($this->payload, 'NOQUEUE')
            ? '/^(?<queue_id>NOQUEUE|[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): (?:milter-)?(?<status>.*): (?:RCPT|END-OF-MESSAGE) from (?<client>[^,]*\[(?<client_ip>.*)\]): (?<response>.*?); from=<?(?<from>[^>,]*)>? to=<?(?<to>[^>,]*)>? proto=(?<proto>.*?) helo=<?(?<helo>[^>,]*)>$/'
            : '/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): ?client=(?<client>[^,]*\[(?<client_ip>.*)\]+)$/';
    }
}

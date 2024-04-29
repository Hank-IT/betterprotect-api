<?php

declare(strict_types=1);

namespace App\Services\MailLogging\LegacyPostfixParser\Parser\Daemons;

class Bounce extends Daemon
{
    protected function getRegex(): string
    {
        return '/^(?<queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11}): (?:sender non-delivery notification): (?<ndn_queue_id>[0-9A-Za-z]{14,16}|[0-9A-F]{10,11})/';
    }
}

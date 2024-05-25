<?php

declare(strict_types=1);

namespace App\Services\PostfixLog\LegacyPostfixParser\Parser\Daemons;

use App\Services\PostfixLog\LegacyPostfixParser\Parser\EncryptionIndex;

abstract class Daemon
{
    protected $payload;

    protected $encryptionIndex;

    public function parse(string $payload, EncryptionIndex $encryptionIndex, string $sysLogTag): array
    {
        $this->payload = $payload;

        $this->encryptionIndex = $encryptionIndex;

        preg_match($this->getRegex(), $this->payload, $result);

        if (isset($result['relay_ip'])) {
            $result = array_merge($result, $this->encryptionIndex->match($result['relay_ip'], $sysLogTag));
        }

        if (isset($result['client_ip'])) {
            $result = array_merge($result, $this->encryptionIndex->match($result['client_ip'], $sysLogTag));
        }

        return empty($result) ? []: $result;
    }

    protected abstract function getRegex(): string;
}

<?php

declare(strict_types=1);

namespace App\Services\PostfixLog\LegacyPostfixParser\Parser;

class EncryptionIndex
{
    protected $index = [];

    public function append(object $row, int $index)
    {
        if (! $result = $this->parse($row->Message)) {
            return false;
        }

        $this->index[$index] = [
            'enc_type' => $result['enc_type'],
            'enc_direction' => $result['enc_direction'],
            'enc_ip' => $result['enc_ip'],
            'enc_cipher' => $result['enc_cipher'],
            'process' => $row->SysLogTag,
        ];

        return true;
    }

    public function match(string $ipAddress, string $syslogTag): array
    {
        $data = array_filter(array_reverse($this->getIndex()), function($data) use($ipAddress, $syslogTag) {
            return $data['process'] == $syslogTag && $data['enc_ip'] == $ipAddress;
        });

        if (isset($data[0])) {
            return $data[0];
        }

        return [];
    }

    public function getIndex(): array
    {
        return $this->index;
    }

    protected function parse(string $payload)
    {
        preg_match($this->getRegex(), $payload, $result);

        return is_array($result) ? $result: [];
    }

    protected function getRegex(): string
    {
        return '/^(?<enc_type>Untrusted|Anonymous|Trusted|Verified) TLS connection established (?<enc_direction>from|to) [^,]*\[(?<enc_ip>.*)\]:(?:.*:)? (?<enc_cipher>[^,]*)$/';
    }
}

<?php

declare(strict_types=1);

namespace App\Services\MailLogging\LegacyPostfixParser\Parser;

use Illuminate\Support\Str;

class Row
{
    protected $daemonNameSpace = 'App\Services\Postfix\Parser\Daemons';

    public function parse(object $row, EncryptionIndex $encryptionIndex)
    {
        $parsedRow = $this->callParserForDaemon(
            $this->getDaemon($row->SysLogTag), $row, $encryptionIndex
        );

        if (! empty($parsedRow)) {
            $parsedRow['reported_at'] = $row->DeviceReportedTime;
        }

        return $parsedRow;
    }

    protected function getDaemon(string $payload): string
    {
        preg_match('/postfix\/(?<daemon>[\w]+)/', $payload, $result);

        return empty($result) ? 'unknown': $result['daemon'];
    }

    protected function callParserForDaemon(string $daemon, object $row, $encryptionIndex): array
    {
        $class = $this->daemonNameSpace . '\\' . Str::ucfirst($daemon);

        if (! class_exists($class)) {
            //Log::debug('No handler for daemon ' . $daemon);

            return [];
        }

        return app($class)->parse($row->Message, $encryptionIndex, $row->SysLogTag);
    }
}

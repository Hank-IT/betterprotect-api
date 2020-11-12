<?php

declare(strict_types=1);

namespace App\Postfix\Parser;

use App\Services\PostfixPolicyInstallation\ClientSenderAccessHandler;
use Illuminate\Support\Str;

class Parser
{
    protected $encryptionIndex;

    protected $row;

    public function __construct(EncryptionIndex $encryptionIndex, Row $row)
    {
        $this->encryptionIndex = $encryptionIndex;

        $this->row = $row;
    }

    public function parse(array $logs): array
    {
        $messages = [];

        foreach($logs as $index => $log) {
            $log->Message = trim($log->Message);

            $this->encryptionIndex->append($log, $index);

            if (empty($parsedRow = $this->row->parse($log, $this->encryptionIndex))) {
                continue;
            }

            $rowQueueId = $this->getQueueId($parsedRow);

            $messages[$rowQueueId] = isset($messages[$rowQueueId])
                ? array_merge($messages[$rowQueueId], $this->processPolicyDecisions($parsedRow))
                : $this->processPolicyDecisions($parsedRow);
        }

        return $messages;
    }

    protected function getQueueId(array $row): string
    {
        if (isset($row['queue_id'])) {
            return $row['queue_id'];
        }

        return strtoupper(uniqid());
    }

    protected function processPolicyDecisions(array $row): array
    {
        if (isset($row['status'])) {
            $row['status'] = Str::lower($row['status']);
        }

        if (! isset($row['response'])) {
            return $row;
        }

        if (Str::contains($row['response'], ClientSenderAccessHandler::POLICY_DENIED)) {
            $row['bp_policy_decision'] = 'reject';
        }

        if (Str::contains($row['response'], ClientSenderAccessHandler::POLICY_GRANTED)) {
            $row['bp_policy_decision'] = 'ok';
        }

        return $row;
    }
}

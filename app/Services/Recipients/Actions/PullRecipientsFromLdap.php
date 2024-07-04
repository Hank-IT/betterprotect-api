<?php

namespace App\Services\Recipients\Actions;

use LdapRecord\Models\ActiveDirectory\Group;
use LdapRecord\Models\ActiveDirectory\User;
use LdapRecord\Query\Model\Builder;

class PullRecipientsFromLdap
{
    public function __construct(protected ProcessLdapEntityAsRecipient $processLdapEntityAsRecipient) {}

    public function execute(array $ignoredDomains = [])
    {
        return array_merge(
            $this->getRecipients(User::query(), $ignoredDomains),
            $this->getRecipients(Group::query(), $ignoredDomains),
        );
    }

    protected function getRecipients(Builder $query, array $ignoredDomains): array
    {
        $emails = [];

        $query->select(['*'])->chunk(1000, function ($entries) use(&$emails, $ignoredDomains) {
            foreach ($entries as $entry) {
                $payload = $this->processLdapEntityAsRecipient->execute($entry, $ignoredDomains);

                if(empty($payload)) {
                    continue;
                }

                $emails = array_merge($emails, $payload);
            }
        });

        return $emails;
    }
}

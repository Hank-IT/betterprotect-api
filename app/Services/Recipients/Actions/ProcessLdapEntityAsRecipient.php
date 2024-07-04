<?php

namespace App\Services\Recipients\Actions;

use LdapRecord\Models\ActiveDirectory\Group;
use LdapRecord\Models\ActiveDirectory\User;

class ProcessLdapEntityAsRecipient
{
    public function __construct(protected SanitizeLdapEmail $sanitizeLdapEmail) {}

    public function execute(User|Group $ldapEntity, array $ignoredDomains = []): array
    {
        $emails = [];

        foreach ($ldapEntity->proxyAddresses as $proxyAddress) {
            $email = $this->sanitizeLdapEmail->execute($proxyAddress, $ignoredDomains);

            if (empty($email)) {
                continue;
            }

            $emails[] = $email;
        }

        return $emails;
    }
}

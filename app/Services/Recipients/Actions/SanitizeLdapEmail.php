<?php

namespace App\Services\Recipients\Actions;

use Illuminate\Support\Str;

class SanitizeLdapEmail
{
    public function execute(string $email, array $ignoredDomains = []): ?string
    {
        $email = $this->removeSmtp(strtolower($email));

        if (! $this->isValidEmail($email)) {
            return null;
        }

        if ($this->hasIgnoredDomain($email, $ignoredDomains)) {
            return null;
        }

        return $email;
    }

    /**
     * Remove 'smtp:' from beginning of the string.
     */
    protected function removeSmtp(string $email): string
    {
        if (Str::startsWith($email, 'smtp:')) {
            $email = str_replace('smtp:', '', $email);
        }

        return $email;
    }

    /**
     * Validate the email address.
     */
    protected function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if email has ignored domain.
     */
    protected function hasIgnoredDomain(string $email, array $ignoredDomains): bool
    {
        if (empty($ignoredDomains)) {
            return false;
        }

        foreach($ignoredDomains as $domain) {
            if (Str::endsWith($email, $domain)) {
                return true;
            }
        }

        return false;
    }
}

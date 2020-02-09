<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class LdapEmailSanitizer
 * @package App\Services
 *
 * ToDo: Needs some refactoring.
 */
class LdapEmailSanitizer
{
    protected $addresses;

    protected $ignoredDomains;

    public function __construct (Collection $addresses, ?string $ignoredDomains)
    {
        $this->addresses = $addresses;

        $this->ignoredDomains = $ignoredDomains;
    }

    public function sanitize()
    {
        Log::debug('LdapEmailSanitizer: ' . $this->addresses->count() . ' addresses to process.');

        $this->addresses->transform(function($address) {
            Log::debug('LdapEmailSanitizer: Processing address ' . $address . '.');

            $address = strtolower($address);

            $address = $this->removeSmtp($address);

            if (! $this->isValidEmail($address)) {
                Log::debug('LdapEmailSanitizer: Address is invalid.');

                return null;
            }

            if (! empty($this->ignoredDomains)) {
                if ($this->hasIgnoredDomain($address)) {
                    Log::debug('LdapEmailSanitizer: Address has ignored domain.');

                    return null;
                }
            }

            Log::debug('LdapEmailSanitizer: Address is valid.');

            return $address;
        });

        $this->addresses = $this->addresses->filter()->values();

        Log::debug('LdapEmailSanitizer: ' . $this->addresses->count() . ' addresses accepted.');

        return $this->addresses;
    }

    /**
     * Remove 'smtp:' from beginning of the string.
     *
     * @param  string $address
     * @return string $address
     */
    protected function removeSmtp($address)
    {
        if (Str::startsWith($address, 'smtp:')) {
            $address = str_replace('smtp:', '', $address);

            Log::debug('LdapEmailSanitizer: Removed "smtp:" from address ' . $address . '.');
        }

        return $address;
    }

    /**
     * Validate the email address.
     *
     * @param $address
     * @return bool
     */
    protected function isValidEmail($address)
    {
        return filter_var($address, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Loop through all ignored domains
     * and remove matching addresses.
     *
     * @param $address
     * @return bool
     */
    protected function hasIgnoredDomain($address)
    {
        $ignoredDomains = collect(explode(',', $this->ignoredDomains));
        $addressLength = strlen($address);

        $ignoredDomains->each(function($domain) use(&$address, $addressLength) {
            if ($domain[0] !== '@') {
                $domain = '@' . $domain;
            }

            $startingPoint = strlen($domain);

            // If the domain has more chars than the
            // address, we surely don't have a match.
            if ($domain > $addressLength) {
                return true;
            }

            // Check if the address ends with a ignored
            // domain and null it, if it does.
            if (substr($address, $addressLength - $startingPoint, $addressLength) === $domain) {
                $address = null;
            }
        });

        return $address === null;
    }
}

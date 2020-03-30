<?php

namespace App\Models;

use App\Services\Settings;
use Adldap\AdldapInterface;
use App\Concerns\SerializesDate;
use Adldap\Connections\Provider;
use Adldap\Schemas\ActiveDirectory;
use Illuminate\Database\Eloquent\Model;

class LdapDirectory extends Model
{
    use SerializesDate;

    protected $fillable = [
        'connection',
        'schema',
        'group_dn',
        'password_sync',
        'account_prefix',
        'account_suffix',
        'discover_attr',
        'authenticate_attr',
        'bind_user',
        'bind_password',
        'servers',
        'port',
        'timeout',
        'base_dn',
        'use_ssl',
        'use_tls',
        'sso_auth_attr',
        'ignored_domains',
    ];

    protected $hidden = [
        'bind_password',
    ];

    protected $appends = [
        'auth_enabled'
    ];

    public function setIgnoredDomainsAttribute($value)
    {
        $this->attributes['ignored_domains'] = str_replace(' ', '', $value);
    }

    public function getAuthEnabledAttribute()
    {
        if (Settings::has('auth_ldap')) {
            if (Settings::get('auth_ldap') === $this->getAttribute('connection')) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function authenticate($username, $password)
    {
        return $this->getLdapConnection()->auth()->attempt($username, $password, true);
    }

    public function setBindPasswordAttribute($value)
    {
        $this->attributes['bind_password'] = encrypt($value);
    }

    public function getBindPasswordAttribute()
    {
        if (empty($this->attributes['bind_password'])) {
            return null;
        }

        return decrypt($this->attributes['bind_password']);
    }

    protected function getLdapConnectionArray(): array
    {
        return [
            // Mandatory Configuration Options
            'hosts'            => explode(',', $this->servers),
            'base_dn'          => $this->base_dn,
            'username'         => $this->bind_user,
            'password'         => $this->bind_password,

            // Optional Configuration Options
            'schema'           => ActiveDirectory::class, // Only AD support for now
            'account_prefix'   => $this->account_prefix,
            'account_suffix'   => $this->account_suffix,
            'port'             => intval($this->port),
            'follow_referrals' => false,
            'use_ssl'          => boolval($this->use_ssl),
            'use_tls'          => boolval($this->use_tls),
            'version'          => 3,
            'timeout'          => intval($this->timeout),
        ];
    }

    public function getLdapConnection(): Provider
    {
        $adldap = app(AdldapInterface::class);

        $adldap->addProvider($this->getLdapConnectionArray(), $this->getAttribute('connection'));

        return $adldap->connect($this->getAttribute('connection'));
    }

    public function isValidForAuthentication()
    {
        if (empty($this->discover_attr)) {
            return false;
        }

        if (empty($this->authenticate_attr)) {
            return false;
        }

        return true;
    }
}

<?php

namespace App\Providers;

use App\Support\Util;
use App\Services\Settings;
use App\Models\LdapDirectory;
use Illuminate\Support\ServiceProvider;

class AuthSettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Util::databaseAvailable()) {
            if (Util::ldapAuthEnabled()) {
                $ldapDirectory = LdapDirectory::where('connection', '=', Settings::get('auth_ldap'))->first();

                if (! is_null($ldapDirectory)) {
                    config([
                        'auth.providers.users.driver' => 'ldap',
                        'ldap.connections.default.auto_connect' => true,
                        'ldap.connections.default.schema' => config('auth.ldap_schemas.' . $ldapDirectory->schema . '.class'),
                        'ldap.connections.default.settings.account_prefix' => $ldapDirectory->account_prefix,
                        'ldap.connections.default.settings.account_suffix' => $ldapDirectory->account_suffix,
                        'ldap.connections.default.settings.hosts' => explode(',', $ldapDirectory->servers),
                        'ldap.connections.default.settings.port' => $ldapDirectory->port,
                        'ldap.connections.default.settings.timeout' => $ldapDirectory->timeout,
                        'ldap.connections.default.settings.base_dn' => $ldapDirectory->base_dn,
                        'ldap.connections.default.settings.username' => $ldapDirectory->bind_user,
                        'ldap.connections.default.settings.password' => $ldapDirectory->bind_password,
                        'ldap.connections.default.settings.use_ssl' => boolval($ldapDirectory->use_ssl),
                        'ldap.connections.default.settings.use_tls' => boolval($ldapDirectory->use_tls),
                        'ldap_auth.identifiers.ldap.locate_users_by' => $ldapDirectory->discover_attr,
                        'ldap_auth.identifiers.ldap.bind_users_by' => $ldapDirectory->authenticate_attr,
                        'ldap_auth.identifiers.windows.locate_users_by' => $ldapDirectory->discover_attr,
                        'ldap_auth.identifiers.windows.server_key' => $ldapDirectory->sso_auth_attr,
                        'ldap_auth.passwords.sync' => $ldapDirectory->password_sync,
                        'ldap_auth.ldap_login_group_dn' => $ldapDirectory->password_sync,
                    ]);

                    if (Settings::has('login_fallback')) {
                        config(['ldap_auth.login_fallback' => Settings::has('login_fallback')]);
                    }
                }
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

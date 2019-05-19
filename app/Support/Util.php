<?php

namespace App\Support;

use App\Models\LdapDirectory;
use App\Services\Settings;

class Util
{
    public static function databaseAvailable($name = null)
    {
        try {
            \Illuminate\Support\Facades\DB::connection($name)->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public static function ldapAuthEnabled()
    {
        return Settings::has('auth_ldap');
    }

    public static function activeLdapAuthDirectory()
    {
        return LdapDirectory::where('connection', '=', Settings::get('auth_ldap'))->first();
    }
}
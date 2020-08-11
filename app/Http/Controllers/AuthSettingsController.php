<?php

namespace App\Http\Controllers;

use App\Support\Util;
use App\Services\Settings;
use Illuminate\Http\Request;
use App\Models\LdapDirectory;
use App\Exceptions\ErrorException;

class AuthSettingsController extends Controller
{
    public function ldap(Request $request)
    {
        if ($request->ldap_directory == null) {
            Settings::forget('auth_ldap');

            return response()->json([
                'status' => 'success',
                'message' => 'LDAP Authentifizierung wurde erfolgreich deaktiviert.',
                'data' => [],
            ]);
        }

        $request->validate([
            'ldap_directory' => 'required|integer|exists:ldap_directories,id',
            'username' => 'required|string|max:5000',
            'password' => 'required|string|max:5000',
        ]);

        $ldapDirectory = LdapDirectory::findOrFail($request->ldap_directory);

        if (! $ldapDirectory->isValidForAuthentication()) {
            throw new ErrorException('Das LDAP Directory ist für die Authentifizierung ungültig. Bitte Einstellungen überprüfen.');
        }

        if (! $ldapDirectory->authenticate($request->username, $request->password)) {
            throw new ErrorException('LDAP Authentifizierung konnte nicht aktiviert werden. Bitte Einstellungen überprüfen.');
        }

        Settings::set('auth_ldap', $ldapDirectory->connection);

        return response()->json([
            'status' => 'success',
            'message' => 'LDAP Authentifizierung wurde erfolgreich aktiviert.',
            'data' => [],
        ]);
    }

    public function activeLdap()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'null',
            'data' => optional(Util::activeLdapAuthDirectory())->id,
        ]);
    }

    public function authFallback(Request $request)
    {
        $this->validate($request, [
            'login_fallback' => 'required|boolean',
        ]);

        if (boolval($request->login_fallback)) {
            Settings::set('login_fallback', $request->login_fallback);
        } else {
            Settings::forget('login_fallback');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Gespeichert',
            'data' => [],
        ]);
    }

    public function authFallbackActive()
    {
        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => Settings::has('login_fallback')
        ]);
    }
}

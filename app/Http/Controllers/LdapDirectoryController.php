<?php

namespace App\Http\Controllers;

use App\Models\ClientSenderAccess;
use Illuminate\Http\Request;
use App\Models\LdapDirectory;
use App\Exceptions\ErrorException;
use Symfony\Component\HttpFoundation\Response;

class LdapDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'currentPage' => 'nullable|int',
            'perPage' => 'nullable|int',
        ]);

        if ($request->filled('currentPage', 'perPage')) {
            if ($request->filled('search')) {
                $ldapDirectory = LdapDirectory::where('connection', 'LIKE', '%' . $request->search . '%');
            } else {
                $ldapDirectory = LdapDirectory::query();
            }

            return response()->json([
                'status' => 'success',
                'message' => null,
                'data' => $ldapDirectory->paginate($request->perPage, ['*'], 'page', $request->currentPage),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => LdapDirectory::all(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'connection' => 'required|string|max:191|unique:ldap_directories,connection',
            'servers' => 'required|string|max:191',
            'port' => 'required|integer|max:65535',
            'timeout' => 'required|integer',
            'base_dn' => 'required|string|max:191',
            'bind_user' => 'required|string|max:191',
            'bind_password' => 'required|string|max:191',
            'use_ssl' => 'nullable|boolean',
            'use_tls' => 'nullable|boolean',

            'ignored_domains' => 'nullable|string|max:50000',

            // Auth settings
            'group_dn' => 'nullable|string|max:191',
            'password_sync' => 'nullable|boolean',
            'account_prefix' => 'nullable|string|max:191',
            'account_suffix' => 'nullable|string|max:191',
            'discover_attr' => 'nullable|string|max:191',
            'authenticate_attr' => 'nullable|string|max:191',
            'sso_auth_attr' => 'nullable|string|max:191',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'LDAP Directory wurde erfolgreich erstellt.',
            'data' => LdapDirectory::create($request->all()),
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, LdapDirectory $ldapDirectory)
    {
        $this->validate($request, [
            'connection' => 'required|string|max:191|unique:ldap_directories,connection,' . $ldapDirectory->id,
            'servers' => 'required|string|max:191',
            'port' => 'required|integer|max:65535',
            'timeout' => 'required|integer',
            'base_dn' => 'required|string|max:191',
            'bind_user' => 'required|string|max:191',
            'bind_password' => 'nullable|string|max:191',
            'use_ssl' => 'nullable|boolean',
            'use_tls' => 'nullable|boolean',

            'ignored_domains' => 'nullable|string|max:50000',

            // Auth settings
            'group_dn' => 'nullable|string|max:191',
            'password_sync' => 'nullable|boolean',
            'account_prefix' => 'nullable|string|max:191',
            'account_suffix' => 'nullable|string|max:191',
            'discover_attr' => 'nullable|string|max:191',
            'authenticate_attr' => 'nullable|string|max:191',
            'sso_auth_attr' => 'nullable|string|max:191',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'LDAP Directory wurde erfolgreich aktualisiert.',
            'data' => $ldapDirectory->update($request->all()),
        ]);
    }

    public function destroy(LdapDirectory $ldapDirectory)
    {
        if ($ldapDirectory->auth_enabled) {
            throw new ErrorException('Das Ldap Directory wird für die Anmeldung genutzt und kann nicht gelöscht werden.');
        }

        $ldapDirectory->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ldap Directory wurde erfolgreich entfernt.',
            'data' => [],
        ]);
    }

    public function show(LdapDirectory $ldapDirectory)
    {
        return $ldapDirectory;
    }
}

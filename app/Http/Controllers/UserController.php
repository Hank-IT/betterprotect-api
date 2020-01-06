<?php

namespace App\Http\Controllers;

use App\Models\ClientSenderAccess;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        if ($request->filled('search')) {
            $user = User::where('username', 'LIKE', '%' . $request->search . '%');
        } else {
            $user = User::query();
        }

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $user->paginate($request->perPage, ['*'], 'page', $request->currentPage),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|confirmed',
            'email' => 'nullable|email|string',
            'role' => 'required|in:readonly,authorizer,editor,administrator',
        ]);

        $user = User::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Der Benutzer wurde erfolgreich erstellt.',
            'data' => $user,
        ], Response::HTTP_CREATED);
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|confirmed',
            'email' => 'nullable|email|string',
            'role' => 'required|in:readonly,authorizer,editor,administrator',
        ]);

        // Prevent password change for ldap users
        if ($user->objectguid) {
            $request->request->remove('password');
        }

        $user->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Der Benutzer wurde erfolgreich aktualisiert.',
            'data' => $user,
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            throw new ErrorException('Sie können sich nicht selbst löschen.');
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Der Benutzer wurde erfolgreich gelöscht.',
            'data' => $user,
        ]);
    }
}

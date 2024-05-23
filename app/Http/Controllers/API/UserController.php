<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Authentication\Models\User;
use App\Services\User\Actions\CreateUser;
use App\Services\User\Actions\DeleteUser;
use App\Services\User\Actions\UpdateUser;
use App\Services\User\Resources\UserResource;
use App\Services\Users\Actions\PaginateUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request, PaginateUsers $paginateUsers)
    {
        $data = $request->validate([
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
            'search' => ['nullable', 'string'],
        ]);


        return UserResource::collection(
            $paginateUsers->execute($data['page_number'], $data['page_size'], $data['search'] ?? null)
        );
    }

    public function store(Request $request,CreateUser $createUser)
    {
        $data = $request->validate([
            'username' => ['required', 'string', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'email' => ['nullable', 'email'],
            'role' => ['required', Rule::in(['readonly', 'authorizer', 'editor', 'administrator'])],
        ]);

        $user = $createUser->execute(
            $data['username'], $data['password'], $data['role'], $data['email'] ?? null,
        );

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user, UpdateUser $updateUser)
    {
        $data = $request->validate([
            'email' => ['nullable', 'email'],
            'role' => ['required', Rule::in(['readonly', 'authorizer', 'editor', 'administrator'])],
        ]);

        $updateUser->execute($user, $data['role'], $data['email'] ?? null);

        return response(status: 200);
    }

    public function destroy(User $user, DeleteUser $deleteUser)
    {
        if ($user->id === Auth::id()) {
            return response(status: 422);
        }

        $deleteUser->execute($user);

        return response(status: 200);
    }
}

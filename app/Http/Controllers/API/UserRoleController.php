<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Authentication\Models\User;
use App\Services\User\Actions\UpdateUserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserRoleController extends Controller
{
    public function __invoke(Request $request, User $user, UpdateUserRole $updateUserRole)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['readonly', 'authorizer', 'editor', 'administrator'])],
        ]);

        $updateUserRole->execute($user, $data['role']);

        return response(status: 200);
    }
}

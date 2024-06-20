<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Authentication\Models\User;
use App\Services\User\Actions\UpdateUserPassword;
use App\Services\User\Actions\UpdateUserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserPasswordController extends Controller
{
    public function __invoke(Request $request, User $user, UpdateUserPassword $updateUserPassword)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $updateUserPassword->execute($user, $data['password']);

        return response(status: 200);
    }
}

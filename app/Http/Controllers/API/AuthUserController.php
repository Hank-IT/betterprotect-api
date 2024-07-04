<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\User\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthUserController extends Controller
{
    public function __invoke()
    {
        return new UserResource(Auth::user());
    }
}

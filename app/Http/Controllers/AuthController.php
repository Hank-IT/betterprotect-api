<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'message' => trans('auth.failed'),
                'data' => [],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response(['status' => 'success'])->header('Authorization', $token);
    }

    public function user()
    {
        return response([
            'status' => 'success',
            'message' => null,
            'data' => Auth::user()
        ]);
    }

    public function refresh()
    {
        return response([
            'status' => 'success',
            'message' => null,
            'data' => [],
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate();

        return response([
            'status' => 'success',
            'message' => null,
            'data' => [],
        ]);
    }
}

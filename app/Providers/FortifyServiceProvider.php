<?php

namespace App\Providers;

use App\Services\Authentication\Fortify\FailedTwoFactorLoginResponse;
use App\Services\Authentication\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Fortify::ignoreRoutes();
    }

    public function boot(): void
    {
        Fortify::authenticateUsing(function ($request) {
            $validated = Auth::validate([
                'samaccountname' => $request->input('username'),
                'password' => $request->input('password'),
                'fallback' => [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                ],
            ]);

            return $validated ? Auth::getLastAttempted() : null;
        });

        $this->app->bind(FailedTwoFactorLoginResponseContract::class, FailedTwoFactorLoginResponse::class);

        $this->configureRoutes();
    }

    protected function configureRoutes(): void
    {
        Route::group([
            'namespace' => 'Laravel\Fortify\Http\Controllers',
            'domain' => config('fortify.domain'),
            'prefix' => config('fortify.prefix'),
        ], function () {
            $this->loadRoutesFrom(base_path('routes/api/fortify.php'));
        });
    }
}

<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    Route::post('/v1/login', [AuthenticatedSessionController::class, 'store'])->name('api.v1.auth.login');

    Route::post('/v1/logout', [AuthenticatedSessionController::class, 'destroy'])->name('api.v1.auth.logout');
});

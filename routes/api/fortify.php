<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('api.v1.auth.login');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('api.v1.auth.logout');
});

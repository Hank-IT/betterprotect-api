<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ServerController;
use App\Http\Controllers\API\Policy\RuleController;
use App\Http\Controllers\API\Policy\RecipientController;

Route::get('/v1/servers', [ServerController::class, 'index'])->name('api.v1.server.index')->middleware('role:editor');
Route::get('/v1/servers/{server}', [ServerController::class, 'show'])->name('api.v1.server.show')->middleware('role:editor');
Route::post('/v1/servers', [ServerController::class, 'store'])->name('api.v1.server.store')->middleware('role:administrator');
Route::put('/v1/servers/{server}', [ServerController::class, 'update'])->name('api.v1.server.update')->middleware('role:administrator');
Route::delete('/v1/servers/{server}', [ServerController::class, 'destroy'])->name('api.v1.server.destroy')->middleware('role:administrator');


Route::get('/v1/rules', [RuleController::class, 'index'])->name('api.v1.rule.index')->middleware('role:editor');
Route::post('/v1/rules', [RuleController::class, 'store'])->name('api.v1.rule.store')->middleware('role:editor');
Route::delete('/v1/rules/{clientSenderAccess}', [RuleController::class, 'destroy'])->name('api.v1.rule.destroy')->middleware('role:editor');

Route::get('/v1/recipients', [RecipientController::class, 'index'])->name('api.v1.recipients.index')->middleware('role:editor');
Route::post('/v1/recipients', [RecipientController::class, 'store'])->name('api.v1.recipients.store')->middleware('role:editor');
Route::delete('/v1/recipients', [RecipientController::class, 'destroy'])->name('api.v1.recipients.destroy')->middleware('role:editor');

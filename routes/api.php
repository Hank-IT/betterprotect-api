<?php

use App\Http\Controllers\API\Policy\RecipientController;
use App\Http\Controllers\API\Policy\RuleController;
use App\Http\Controllers\API\Policy\TransportController;
use App\Http\Controllers\API\Server\ServerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Policy\MilterController;
use App\Http\Controllers\API\Policy\MilterExceptionController;
use App\Http\Controllers\API\Policy\RelayDomainController;
use App\Http\Controllers\API\Policy\OrderableController;
use App\Http\Controllers\API\Policy\ActivatableController;

Route::get('/v1/servers', [ServerController::class, 'index'])->name('api.v1.server.index')->middleware('role:readonly');
Route::get('/v1/servers/{server}', [ServerController::class, 'show'])->name('api.v1.server.show')->middleware('role:readonly');
Route::post('/v1/servers', [ServerController::class, 'store'])->name('api.v1.server.store')->middleware('role:administrator');
Route::put('/v1/servers/{server}', [ServerController::class, 'update'])->name('api.v1.server.update')->middleware('role:administrator');
Route::delete('/v1/servers/{server}', [ServerController::class, 'destroy'])->name('api.v1.server.destroy')->middleware('role:administrator');

Route::get('/v1/rules', [RuleController::class, 'index'])->name('api.v1.rule.index')->middleware('role:readonly');
Route::post('/v1/rules', [RuleController::class, 'store'])->name('api.v1.rule.store')->middleware('role:authorizer');
Route::delete('/v1/rules/{clientSenderAccess}', [RuleController::class, 'destroy'])->name('api.v1.rule.destroy')->middleware('role:authorizer');

Route::get('/v1/recipients', [RecipientController::class, 'index'])->name('api.v1.recipients.index')->middleware('role:readonly');
Route::post('/v1/recipients', [RecipientController::class, 'store'])->name('api.v1.recipients.store')->middleware('role:authorizer');
Route::delete('/v1/recipients', [RecipientController::class, 'destroy'])->name('api.v1.recipients.destroy')->middleware('role:authorizer');

Route::get('/v1/transports', [TransportController::class, 'index'])->name('api.v1.transports.index')->middleware('role:readonly');
Route::post('/v1/transports', [TransportController::class, 'store'])->name('api.v1.transports.store')->middleware('role:editor');
Route::delete('/v1/transports', [TransportController::class, 'destroy'])->name('api.v1.transports.destroy')->middleware('role:editor');

Route::get('/v1/milter', [MilterController::class, 'index'])->middleware('role:readonly')->name('api.v1.milter.index');
Route::post('/v1/milter', [MilterController::class, 'store'])->middleware('role:authorizer')->name('api.v1.milter.store');
Route::delete('/v1/milter/{milter}', [MilterController::class, 'destroy'])->middleware('role:authorizer')->name('api.v1.milter.destroy');

Route::get('/v1/milter/exception', [MilterExceptionController::class, 'index'])->middleware('role:readonly')->name('api.v1.milter.exception.index');
Route::post('/v1/milter/exception', [MilterExceptionController::class, 'store'])->middleware('role:authorizer')->name('api.v1.milter.exception.store');
Route::delete('/v1/milter/exception/{milterException}', [MilterExceptionController::class, 'destroy'])->middleware('role:authorizer')->name('api.v1.milter.exception.destroy');

Route::get('v1//relay-domain', [RelayDomainController::class, 'index'])->middleware('role:readonly')->name('api.v1.relay-domain.index');
Route::post('v1//relay-domain', [RelayDomainController::class, 'store'])->middleware('role:editor')->name('api.v1.relay-domain.store');
Route::delete('v1//relay-domain/{relayDomain}', [RelayDomainController::class, 'destroy'])->middleware('role:editor')->name('api.v1.relay-domain.destroy');

Route::patch('v1/order/{orderableEntitiesEnum}/{id}/{mode}', OrderableController::class)->middleware('role:editor')->name('api.v1.order.store');

Route::patch('v1/active/{activatableEntitiesEnum}/{id}', ActivatableController::class)->middleware('role:editor')->name('api.v1.activation.update');

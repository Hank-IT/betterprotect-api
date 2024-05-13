<?php

use App\Http\Controllers\Charts\MailFlowChartController;
use App\Http\Controllers\MailLogging\LegacyServerLogController;
use App\Http\Controllers\MailLogging\ServerLogController;
use App\Http\Controllers\ServerQueueController;
use App\Http\Controllers\ServerSchemaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhoisController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function(){
    /* Websockets */
    Broadcast::routes();

    /**
     *
     * Server Mail log
     */
    Route::get('/server/log/legacy', [LegacyServerLogController::class, 'index'])->middleware('role:readonly')->name('server.log.show.legacy');
    Route::get('/server/log', ServerLogController::class)->middleware('role:readonly')->name('server.log.show');

    /**
     * Server Mail Queue
     */
    Route::get('/server/queue', [ServerQueueController::class, 'index'])->middleware('role:readonly')->name('server.queue.show');
    Route::post('/server/queue', [ServerQueueController::class, 'store'])->middleware('role:editor')->name('server.queue.store');
    Route::delete('/server/{server}/queue', [ServerQueueController::class, 'destroy'])->middleware('role:editor')->name('server.queue.destroy');

    /**
     * Server Database Schema
     */
    Route::post('/server/{server}/schema', [ServerSchemaController::class, 'store'])->middleware('role:editor')->name('server.schema.store');
    Route::get('/server/{server}/schema', [ServerSchemaController::class, 'show'])->middleware('role:readonly')->name('server.schema.show');

    /**
     * User
     */
    Route::get('/user', [UserController::class, 'index'])->middleware('role:administrator')->name('user.index');
    Route::post('/user', [UserController::class, 'store'])->middleware('role:administrator')->name('user.store');
    Route::get('/user/{user}', [UserController::class, 'show'])->middleware('role:administrator')->name('user.show');
    Route::patch('/user/{user}', [UserController::class, 'update'])->middleware('role:administrator')->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->middleware('role:administrator')->name('user.destroy');

    /**
     * Whois
     */
    Route::post('/whois', WhoisController::class)->middleware('role:readonly')->name('whois.show');

    /**
     * Charts
     */
    Route::get('/charts/mail-flow', MailFlowChartController::class);
});

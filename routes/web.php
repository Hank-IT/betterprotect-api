<?php

use App\Http\Controllers\Charts\MailFlowChartController;
use App\Http\Controllers\MailLogging\LegacyServerLogController;
use App\Http\Controllers\MailLogging\ServerLogController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function(){
    /* Websockets */
    Broadcast::routes();

    Route::get('/server/log/legacy', [LegacyServerLogController::class, 'index'])->middleware('role:readonly')->name('server.log.show.legacy');
    Route::get('/server/log', ServerLogController::class)->middleware('role:readonly')->name('server.log.show');

    Route::get('/charts/mail-flow', MailFlowChartController::class);
});

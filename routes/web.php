<?php

use App\Http\Controllers\AccessPriorityController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthSettingsController;
use App\Http\Controllers\Charts\MailFlowChartController;
use App\Http\Controllers\LdapDirectoryController;
use App\Http\Controllers\MailLogging\LegacyServerLogController;
use App\Http\Controllers\MailLogging\ServerLogController;
use App\Http\Controllers\MilterController;
use App\Http\Controllers\MilterExceptionController;
use App\Http\Controllers\MilterExceptionPriorityController;
use App\Http\Controllers\PolicyInstallationController;
use App\Http\Controllers\RecipientLdapController;
use App\Http\Controllers\RelayDomainController;
use App\Http\Controllers\ServerQueueController;
use App\Http\Controllers\ServerSchemaController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhoisController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/', AppController::class);

Route::group(['middleware' => 'auth:sanctum'], function(){
    /* Websockets */
    Broadcast::routes();

    /**
     * Tasks
     */
    Route::get('/task', [TaskController::class, 'index'])->middleware('role:readonly')->name('task.index');

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
     * ClientSender Access
     */
    Route::post('/access/{clientSenderAccess}/move-up', [AccessPriorityController::class, 'moveUp'])->middleware('role:authorizer')->name('access.moveUp');
    Route::post('/access/{clientSenderAccess}/move-down', [AccessPriorityController::class, 'moveDown'])->middleware('role:authorizer')->name('access.moveDown');

    /**
     * Policy Push
     */
    Route::post('/policy', [PolicyInstallationController::class, 'store'])->middleware('role:authorizer')->name('policy.store');

    /**
     * Relay Domains
     */
    Route::get('/relay-domain', [RelayDomainController::class, 'index'])->middleware('role:readonly')->name('relay-domain.index');
    Route::post('/relay-domain', [RelayDomainController::class, 'store'])->middleware('role:editor')->name('relay-domain.store');
    Route::delete('/relay-domain/{relayDomain}', [RelayDomainController::class, 'destroy'])->middleware('role:editor')->name('relay-domain.destroy');

    /**
     * Milter Exceptions
     */
    Route::get('/milter/exception', [MilterExceptionController::class, 'index'])->middleware('role:authorizer')->name('milter.exception.index');
    Route::post('/milter/exception', [MilterExceptionController::class, 'store'])->middleware('role:authorizer')->name('milter.exception.store');
    Route::delete('/milter/exception/{exception}', [MilterExceptionController::class, 'destroy'])->middleware('role:authorizer')->name('milter.exception.destroy');
    Route::post('/milter/exception/{exception}/move-up', [MilterExceptionPriorityController::class, 'moveUp'])->middleware('role:authorizer')->name('milter.exception.moveUp');
    Route::post('/milter/exception/{exception}/move-down', [MilterExceptionPriorityController::class, 'moveDown'])->middleware('role:authorizer')->name('milter.exception.moveDown');

    /**
     * Milter
     */
    Route::get('/milter', [MilterController::class, 'index'])->middleware('role:authorizer')->name('milter.index');
    Route::post('/milter', [MilterController::class, 'store'])->middleware('role:authorizer')->name('milter.store');
    Route::delete('/milter/{milter}', [MilterController::class, 'destroy'])->middleware('role:authorizer')->name('milter.destroy');

    /**
     * RecipientAccessLdap
     */
    Route::post('/recipient/ldap/{ldapDirectory}', RecipientLdapController::class)->middleware('role:editor')->name('recipient.ldap');

    /**
     * Settings
     */
    Route::post('/settings/auth/ldap', [AuthSettingsController::class, 'ldap'])->middleware('role:administrator')->name('settings.auth.ldap');
    Route::get('/settings/auth/ldap', [AuthSettingsController::class, 'activeLdap'])->middleware('role:administrator')->name('settings.auth.ldap-active');
    Route::post('/settings/auth/fallback', [AuthSettingsController::class, 'authFallback'])->middleware('role:administrator')->name('settings.auth.fallback');
    Route::get('/settings/auth/fallback', [AuthSettingsController::class, 'authFallbackActive'])->middleware('role:administrator')->name('settings.auth.fallback-active');

    /**
     * User
     */
    Route::get('/user', [UserController::class, 'index'])->middleware('role:administrator')->name('user.index');
    Route::post('/user', [UserController::class, 'store'])->middleware('role:administrator')->name('user.store');
    Route::get('/user/{user}', [UserController::class, 'show'])->middleware('role:administrator')->name('user.show');
    Route::patch('/user/{user}', [UserController::class, 'update'])->middleware('role:administrator')->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->middleware('role:administrator')->name('user.destroy');

    /**
     * Ldap
     */
    Route::get('/ldap', [LdapDirectoryController::class, 'index'])->middleware('role:administrator')->name('ldap.index');
    Route::post('/ldap', [LdapDirectoryController::class, 'store'])->middleware('role:administrator')->name('ldap.store');
    Route::get('/ldap/{ldapDirectory}', [LdapDirectoryController::class, 'show'])->middleware('role:administrator')->name('ldap.show');
    Route::patch('/ldap/{ldapDirectory}', [LdapDirectoryController::class,', update'])->middleware('role:administrator')->name('ldap.update');
    Route::delete('/ldap/{ldapDirectory}', [LdapDirectoryController::class, 'destroy'])->middleware('role:administrator')->name('ldap.destroy');

    /**
     * Whois
     */
    Route::post('/whois', WhoisController::class)->middleware('role:readonly')->name('whois.show');

    /**
     * Activation
     */
    Route::post('/activation/{id}', [ActivationController::class, 'store'])->middleware('role:editor')->name('activation.store');
    Route::patch('/activation/{id}', [ActivationController::class, 'update'])->middleware('role:editor')->name('activation.update');

    /**
     * Charts
     */
    Route::get('/charts/mail-flow', MailFlowChartController::class);
});

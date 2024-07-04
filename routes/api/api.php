<?php

use App\Http\Controllers\API\AuthUserController;
use App\Http\Controllers\API\BetterprotectPolicyController;
use App\Http\Controllers\API\Logging\AggregatedLogController;
use App\Http\Controllers\API\Policy\ActivatableController;
use App\Http\Controllers\API\Policy\MilterController;
use App\Http\Controllers\API\Policy\MilterExceptionController;
use App\Http\Controllers\API\Policy\OrderableController;
use App\Http\Controllers\API\Policy\RecipientController;
use App\Http\Controllers\API\Policy\RecipientLdapController;
use App\Http\Controllers\API\Policy\RelayDomainController;
use App\Http\Controllers\API\Policy\RuleController;
use App\Http\Controllers\API\Policy\TransportController;
use App\Http\Controllers\API\PostfixQueueController;
use App\Http\Controllers\API\PostfixQueueCountController;
use App\Http\Controllers\API\Server\ServerController;
use App\Http\Controllers\API\Server\ServerSchemaController;
use App\Http\Controllers\API\Server\ServerStateController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\TaskRunningController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserPasswordController;
use App\Http\Controllers\API\UserRoleController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Logging\RawLogController;
use App\Http\Controllers\API\PostfixQueueRefreshController;
use App\Http\Controllers\API\Server\ServerSyslogController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Broadcast::routes();

    Route::get('/v1/auth-user', AuthUserController::class)->name('api.v1.auth-user.show');

    Route::get('/v1/servers', [ServerController::class, 'index'])->name('api.v1.server.index')->middleware('role:readonly');
    Route::get('/v1/servers/{server}', [ServerController::class, 'show'])->name('api.v1.server.show')->middleware('role:readonly');
    Route::post('/v1/servers', [ServerController::class, 'store'])->name('api.v1.server.store')->middleware('role:administrator');
    Route::put('/v1/servers/{server}', [ServerController::class, 'update'])->name('api.v1.server.update')->middleware('role:administrator');
    Route::delete('/v1/servers/{server}', [ServerController::class, 'destroy'])->name('api.v1.server.destroy')->middleware('role:administrator');
    Route::get('/v1/servers/{server}/state', ServerStateController::class)->name('api.v1.server.state')->middleware('role:readonly');
    Route::get('/v1/servers/{server}/log', ServerSyslogController::class)->name('api.v1.server.log')->middleware('role:readonly');

    Route::get('/v1/servers/{server}/postfix-queue/count', PostfixQueueCountController::class)->name('api.v1.server.postfix-queue.count')->middleware('role:readonly');

    Route::get('/v1/servers/{server}/postfix-queue', [PostfixQueueController::class, 'index'])->name('api.v1.server.postfix-queue.index')->middleware('role:readonly');
    Route::get('/v1/servers/{server}/postfix-queue/{queueId}', [PostfixQueueController::class, 'show'])->name('api.v1.server.postfix-queue.]')->middleware('role:readonly');
    Route::post('/v1/servers/{server}/postfix-queue', [PostfixQueueController::class, 'store'])->name('api.v1.server.postfix-queue.flush')->middleware('role:editor');
    Route::delete('/v1/servers/{server}/postfix-queue', [PostfixQueueController::class, 'destroy'])->name('api.v1.server.postfix-queue.destroy')->middleware('role:editor');
    Route::post('/v1/servers/{server}/postfix-queue/refresh', PostfixQueueRefreshController::class)->name('api.v1.server.postfix-queue.refresh')->middleware('role:editor');

    Route::get('/server/{server}/schema', [ServerSchemaController::class, 'show'])->name('api.v1.server.schema.check')->middleware('role:readonly');
    Route::post('/server/{server}/schema', [ServerSchemaController::class, 'store'])->name('api.v1.server.schema.migrate')->middleware('role:editor');

    Route::get('/v1/policy/rules', [RuleController::class, 'index'])->name('api.v1.rule.index')->middleware('role:readonly');
    Route::post('/v1/policy/rules', [RuleController::class, 'store'])->name('api.v1.rule.store')->middleware('role:authorizer');
    Route::delete('/v1/policy/rules/{clientSenderAccess}', [RuleController::class, 'destroy'])->name('api.v1.rule.destroy')->middleware('role:authorizer');

    Route::post('/v1/policy/installation', BetterprotectPolicyController::class)->middleware('role:authorizer')->name('api.v1.policy.installation');

    Route::get('/v1/policy/recipients', [RecipientController::class, 'index'])->name('api.v1.recipients.index')->middleware('role:readonly');
    Route::post('/v1/policy/recipients', [RecipientController::class, 'store'])->name('api.v1.recipients.store')->middleware('role:authorizer');
    Route::delete('/v1/policy/recipients', [RecipientController::class, 'destroy'])->name('api.v1.recipients.destroy')->middleware('role:authorizer');

    Route::post('/v1/policy/recipients/ldap', RecipientLdapController::class)->name('api.v1.recipients.ldap')->middleware('role:editor');

    Route::get('/v1/policy/transports', [TransportController::class, 'index'])->name('api.v1.transports.index')->middleware('role:readonly');
    Route::post('/v1/policy/transports', [TransportController::class, 'store'])->name('api.v1.transports.store')->middleware('role:editor');
    Route::delete('/v1/policy/transports', [TransportController::class, 'destroy'])->name('api.v1.transports.destroy')->middleware('role:editor');

    Route::get('/v1/policy/milter', [MilterController::class, 'index'])->middleware('role:readonly')->name('api.v1.milter.index');
    Route::post('/v1/policy/milter', [MilterController::class, 'store'])->middleware('role:authorizer')->name('api.v1.milter.store');
    Route::delete('/v1/policy/milter/{milter}', [MilterController::class, 'destroy'])->middleware('role:authorizer')->name('api.v1.milter.destroy');

    Route::get('/v1/policy/milter/exception', [MilterExceptionController::class, 'index'])->middleware('role:readonly')->name('api.v1.milter.exception.index');
    Route::post('/v1/policy/milter/exception', [MilterExceptionController::class, 'store'])->middleware('role:authorizer')->name('api.v1.milter.exception.store');
    Route::delete('/v1/policy/milter/exception/{milterException}', [MilterExceptionController::class, 'destroy'])->middleware('role:authorizer')->name('api.v1.milter.exception.destroy');

    Route::get('v1/policy/relay-domain', [RelayDomainController::class, 'index'])->middleware('role:readonly')->name('api.v1.relay-domain.index');
    Route::post('v1/policy/relay-domain', [RelayDomainController::class, 'store'])->middleware('role:editor')->name('api.v1.relay-domain.store');
    Route::delete('v1/policy/relay-domain/{relayDomain}', [RelayDomainController::class, 'destroy'])->middleware('role:editor')->name('api.v1.relay-domain.destroy');

    Route::patch('v1/policy/order/{orderableEntitiesEnum}/{id}/{mode}', OrderableController::class)->middleware('role:authorizer')->name('api.v1.order.store');

    Route::patch('v1/policy/activation/{activatableEntitiesEnum}/{id}', ActivatableController::class)->middleware('role:authorizer')->name('api.v1.activation.update');

    Route::get('v1/tasks', TaskController::class)->middleware('role:readonly')->name('api.v1.tasks.index');
    Route::get('v1/tasks/running', TaskRunningController::class)->middleware('role:readonly')->name('api.v1.tasks.running');

    Route::get('v1/user', [UserController::class, 'index'])->middleware('role:administrator')->name('api.v1.user.index');
    Route::post('v1/user', [UserController::class, 'store'])->middleware('role:administrator')->name('api.v1.user.store');
    Route::patch('v1/user/{user}/role', UserRoleController::class)->middleware('role:administrator')->name('api.v1.user.role.update');
    Route::patch('v1/user/{user}/password', UserPasswordController::class)->middleware('role:administrator')->name('api.v1.user.role.password');
    Route::delete('v1/user/{user}', [UserController::class, 'destroy'])->middleware('role:administrator')->name('api.v1.user.destroy');

    Route::get('v1/logging/aggregated', [AggregatedLogController::class, 'index'])->middleware('role:readonly')->name('api.v1.logging.aggregated.index');
    Route::get('v1/logging/aggregated/{queueId}',  [AggregatedLogController::class, 'show'])->middleware('role:readonly')->name('api.v1.logging.aggregated.show');

    Route::get('v1/logging/raw',  [RawLogController::class, 'index'])->middleware('role:readonly')->name('api.v1.logging.raw.index');
});

<?php

Route::get('/', 'AppController');

Route::group(['middleware' => 'jwt.auth'], function(){
    /**
     * Authentication
     */
    Route::get('auth/user', 'AuthController@user')->middleware('role:readonly')->name('auth.user');

    /**
     * Tasks
     */
    Route::get('/task', 'TaskController@index')->middleware('role:readonly')->name('task.index');

    /**
     * Server Mail log
     */
    Route::get('/server/log', 'ServerLogController@index')->middleware('role:readonly')->name('server.log.show');

    /**
     * Server Mail Queue
     */
    Route::get('/server/queue', 'ServerQueueController@index')->middleware('role:readonly')->name('server.queue.show');
    Route::post('/server/queue', 'ServerQueueController@store')->middleware('role:editor')->name('server.queue.store');
    Route::delete('/server/{server}/queue', 'ServerQueueController@destroy')->middleware('role:editor')->name('server.queue.destroy');

    /**
     * Server
     */
    Route::get('/server', 'ServerController@index')->middleware('role:readonly')->name('server.index');
    Route::delete('/server/{server}', 'ServerController@destroy')->middleware('role:editor')->name('server.destroy');

    /**
     * Server Wizard
     */
    Route::post('/server-wizard', 'Server\ServerController@store')->middleware('role:editor')->name('server-wizard.store');
    Route::post('/server-wizard/{server}/postfix', 'Server\PostfixController@store')->middleware('role:editor')->name('server-wizard.postfix.store');
    Route::post('/server-wizard/{server}/console', 'Server\ConsoleController@store')->middleware('role:editor')->name('server-wizard.console.store');
    Route::post('/server-wizard/{server}/log', 'Server\LogController@store')->middleware('role:editor')->name('server-wizard.log.store');
    Route::post('/server-wizard/{server}/amavis', 'Server\AmavisController@store')->middleware('role:editor')->name('server-wizard.amavis.store');

    /**
     * Server Update
     */
    Route::patch('/server/{server}', 'Server\ServerController@update')->middleware('role:editor')->name('server-wizard.update');
    Route::patch('/server/{server}/postfix', 'Server\PostfixController@update')->middleware('role:editor')->name('server-wizard.postfix.update');
    Route::patch('/server/{server}/console', 'Server\ConsoleController@update')->middleware('role:editor')->name('server-wizard.console.update');
    Route::patch('/server/{server}/log', 'Server\LogController@update')->middleware('role:editor')->name('server-wizard.log.update');
    Route::patch('/server/{server}/amavis', 'Server\AmavisController@update')->middleware('role:editor')->name('server-wizard.amavis.update');

    /**
     * Server Show
     */
    Route::get('/server/{server}', 'Server\ServerController@show')->middleware('role:readonly')->name('server-wizard.show');
    Route::get('/server/{server}/postfix', 'Server\PostfixController@show')->middleware('role:readonly')->name('server-wizard.postfix.show');
    Route::get('/server/{server}/console', 'Server\ConsoleController@show')->middleware('role:readonly')->name('server-wizard.console.show');
    Route::get('/server/{server}/log', 'Server\LogController@show')->middleware('role:readonly')->name('server-wizard.log.show');
    Route::get('/server/{server}/amavis', 'Server\AmavisController@show')->middleware('role:readonly')->name('server-wizard.amavis.show');

    /**
     * Server Database Schema
     */
    Route::post('/server/{server}/schema', 'ServerSchemaController@store')->middleware('role:editor')->name('server.schema.store');
    Route::get('/server/{server}/schema', 'ServerSchemaController@show')->middleware('role:readonly')->name('server.schema.show');

    /**
     * ClientSender Access
     */
    Route::get('/access', 'AccessController@index')->middleware('role:readonly')->name('access.index');
    Route::post('/access', 'AccessController@store')->middleware('role:authorizer')->name('access.store');
    Route::post('/access/{clientSenderAccess}/move-up', 'AccessPriorityController@moveUp')->middleware('role:authorizer')->name('access.moveUp');
    Route::post('/access/{clientSenderAccess}/move-down', 'AccessPriorityController@moveDown')->middleware('role:authorizer')->name('access.moveDown');
    Route::delete('/access/{access}', 'AccessController@destroy')->middleware('role:authorizer')->name('access.destroy');

    /**
     * Policy Push
     */
    Route::post('/policy', 'PolicyInstallationController@store')->middleware('role:authorizer')->name('policy.store');

    /**
     * RecipientAccess
     */
    Route::get('/recipient', 'RecipientController@index')->middleware('role:readonly')->name('recipient.index');
    Route::post('/recipient', 'RecipientController@store')->middleware('role:editor')->name('recipient.store');
    Route::patch('/recipient/{recipient}', 'RecipientController@update')->middleware('role:editor')->name('recipient.update');
    Route::delete('/recipient/{recipient}', 'RecipientController@destroy')->middleware('role:editor')->name('recipient.destroy');

    /**
     * Relay Domains
     */
    Route::get('/relay-domain', 'RelayDomainController@index')->middleware('role:readonly')->name('relay-domain.index');
    Route::post('/relay-domain', 'RelayDomainController@store')->middleware('role:editor')->name('relay-domain.store');
    Route::delete('/relay-domain/{relayDomain}', 'RelayDomainController@destroy')->middleware('role:editor')->name('relay-domain.destroy');

    /**
     * Transport
     */
    Route::get('/transport', 'TransportController@index')->middleware('role:readonly')->name('transport.index');
    Route::post('/transport', 'TransportController@store')->middleware('role:editor')->name('transport.store');
    Route::delete('/transport/{transport}', 'TransportController@destroy')->middleware('role:editor')->name('transport.destroy');

    /**
     * Milter
     */
    Route::get('/milter', 'MilterController@index')->middleware('role:authorizer')->name('milter.index');
    Route::post('/milter', 'MilterController@store')->middleware('role:authorizer')->name('milter.store');
    Route::delete('/milter/{milter}', 'MilterController@destroy')->middleware('role:authorizer')->name('milter.destroy');

    /**
     * RecipientAccessLdap
     */
    Route::post('/recipient/ldap/{ldapDirectory}', 'RecipientLdapController')->middleware('role:editor')->name('recipient.ldap');

    /**
     * Settings
     */
    Route::post('/settings/auth/ldap', 'AuthSettingsController@ldap')->middleware('role:administrator')->name('settings.auth.ldap');
    Route::get('/settings/auth/ldap', 'AuthSettingsController@activeLdap')->middleware('role:administrator')->name('settings.auth.ldap-active');
    Route::post('/settings/auth/fallback', 'AuthSettingsController@authFallback')->middleware('role:administrator')->name('settings.auth.fallback');
    Route::get('/settings/auth/fallback', 'AuthSettingsController@authFallbackActive')->middleware('role:administrator')->name('settings.auth.fallback-active');

    /**
     * User
     */
    Route::get('/user', 'UserController@index')->middleware('role:administrator')->name('user.index');
    Route::post('/user', 'UserController@store')->middleware('role:administrator')->name('user.store');
    Route::get('/user/{user}', 'UserController@show')->middleware('role:administrator')->name('user.show');
    Route::patch('/user/{user}', 'UserController@update')->middleware('role:administrator')->name('user.update');
    Route::delete('/user/{user}', 'UserController@destroy')->middleware('role:administrator')->name('user.destroy');

    /**
     * Ldap
     */
    Route::get('/ldap', 'LdapDirectoryController@index')->middleware('role:administrator')->name('ldap.index');
    Route::post('/ldap', 'LdapDirectoryController@store')->middleware('role:administrator')->name('ldap.store');
    Route::get('/ldap/{ldapDirectory}', 'LdapDirectoryController@show')->middleware('role:administrator')->name('ldap.show');
    Route::patch('/ldap/{ldapDirectory}', 'LdapDirectoryController@update')->middleware('role:administrator')->name('ldap.update');
    Route::delete('/ldap/{ldapDirectory}', 'LdapDirectoryController@destroy')->middleware('role:administrator')->name('ldap.destroy');

    /**
     * Whois
     */
    Route::post('/whois', 'WhoisController')->middleware('role:readonly')->name('whois.show');

    /**
     * Activation
     */
    Route::post('/activation/{id}', 'ActivationController@store')->middleware('role:editor')->name('activation.store');
    Route::patch('/activation/{id}', 'ActivationController@update')->middleware('role:editor')->name('activation.update');
});

/**
 * Authentication
 */

Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'AuthController@refresh')->name('auth.refresh');
});

Route::post('auth/login', 'AuthController@login')->name('auth.login');
Route::post('auth/logout', 'AuthController@logout')->name('auth.logout');

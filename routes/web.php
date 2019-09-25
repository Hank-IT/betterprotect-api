<?php

Route::get('/', 'AppController');

Route::group(['middleware' => 'jwt.auth'], function(){
    /**
     * Authentication
     */
    Route::get('auth/user', 'AuthController@user')->name('auth.user');

    /**
     * Tasks
     */
    Route::get('/task', 'TaskController@index')->name('task.index');

    /**
     * Server Mail log
     */
    Route::get('/server/log', 'ServerLogController@index')->name('server.log.show');

    /**
     * Server
     */
    Route::get('/server', 'ServerController@index')->name('server.index');
    Route::post('/server', 'ServerController@store')->name('server.store');
    Route::get('/server/{server}', 'ServerController@show')->name('server.show');
    Route::patch('/server/{server}', 'ServerController@update')->name('server.update');
    Route::delete('/server/{server}', 'ServerController@destroy')->name('server.destroy');

    /**
     * Server Database Schema
     */
    Route::post('/server/{server}/schema', 'ServerSchemaController@store')->name('server.schema.store');
    Route::get('/server/{server}/schema', 'ServerSchemaController@show')->name('server.schema.show');

    /**
     * Server Terminal
     */
    Route::post('/server/{server}/terminal', 'ServerTerminalController@store')->name('server.terminal.store');
    Route::get('/server/{server}/terminal', 'ServerTerminalController@show')->name('server.terminal.show');

    /**
     * Server Mail queue
     */
    Route::get('/server/{server}/queue', 'ServerQueueController@index')->name('server.queue.show');
    Route::post('/server/{server}/queue', 'ServerQueueController@store')->name('server.queue.store');
    Route::delete('/server/{server}/queue/{queueId}', 'ServerQueueController@destroy')->name('server.queue.destroy');

    /**
     * ClientSender Access
     */
    Route::get('/access', 'AccessController@index')->name('access.index');
    Route::post('/access', 'AccessController@store')->name('access.store');
    Route::delete('/access/{access}', 'AccessController@destroy')->name('access.destroy');

    /**
     * Policy Push
     */
    Route::post('/policy', 'PolicyInstallationController@store')->name('policy.store');

    /**
     * RecipientAccess
     */
    Route::get('/recipient', 'RecipientController@index')->name('recipient.index');
    Route::post('/recipient', 'RecipientController@store')->name('recipient.store');
    Route::patch('/recipient/{recipient}', 'RecipientController@update')->name('recipient.update');
    Route::delete('/recipient/{recipient}', 'RecipientController@destroy')->name('recipient.destroy');

    /**
     * Relay Domains
     */
    Route::get('/relay-domain', 'RelayDomainController@index')->name('relay-domain.index');
    Route::post('/relay-domain', 'RelayDomainController@store')->name('relay-domain.store');
    Route::delete('/relay-domain/{relayDomain}', 'RelayDomainController@destroy')->name('relay-domain.destroy');

    /**
     * Transport
     */
    Route::get('/transport', 'TransportController@index')->name('transport.index');
    Route::post('/transport', 'TransportController@store')->name('transport.store');
    Route::delete('/transport/{transport}', 'TransportController@destroy')->name('transport.destroy');

    /**
     * RecipientAccessLdap
     */
    Route::post('/recipient/ldap/{ldapDirectory}', 'RecipientLdapController')->name('recipient.ldap');

    /**
     * Settings
     */
    Route::post('/settings/auth/ldap', 'AuthSettingsController@ldap')->name('settings.auth.ldap');
    Route::get('/settings/auth/ldap', 'AuthSettingsController@activeLdap')->name('settings.auth.ldap-active');
    Route::post('/settings/auth/fallback', 'AuthSettingsController@authFallback')->name('settings.auth.fallback');
    Route::get('/settings/auth/fallback', 'AuthSettingsController@authFallbackActive')->name('settings.auth.fallback-active');


    /**
     * User
     */
    Route::get('/user', 'UserController@index')->name('user.index');
    Route::post('/user', 'UserController@store')->name('user.store');
    Route::get('/user/{user}', 'UserController@show')->name('user.show');
    Route::patch('/user/{user}', 'UserController@update')->name('user.update');
    Route::delete('/user/{user}', 'UserController@destroy')->name('user.destroy');

    /**
     * Ldap
     */
    Route::get('/ldap', 'LdapDirectoryController@index')->name('ldap.index');
    Route::post('/ldap', 'LdapDirectoryController@store')->name('ldap.store');
    Route::get('/ldap/{ldapDirectory}', 'LdapDirectoryController@show')->name('ldap.show');
    Route::patch('/ldap/{ldapDirectory}', 'LdapDirectoryController@update')->name('ldap.update');
    Route::delete('/ldap/{ldapDirectory}', 'LdapDirectoryController@destroy')->name('ldap.destroy');
});

/**
 * Authentication
 */

Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'AuthController@refresh')->name('auth.refresh');
});

Route::post('auth/login', 'AuthController@login')->name('auth.login');
Route::post('auth/logout', 'AuthController@logout')->name('auth.logout');

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            /**
             * Postfix Feature
             */
            $table->string('db_host')->nullable()->change();
            $table->string('db_name')->nullable()->change();
            $table->string('db_user')->nullable()->change();
            $table->text('db_password')->nullable()->change();
            $table->string('db_port')->nullable()->change();
            $table->boolean('postfix_feature_enabled')->default('0');
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->boolean('active')->default('1');

            /**
             * Postfix log viewer
             */
            $table->string('log_db_host')->nullable();
            $table->string('log_db_name')->nullable();
            $table->string('log_db_user')->nullable();
            $table->text('log_db_password')->nullable();
            $table->string('log_db_port')->nullable();

            $table->boolean('log_feature_enabled')->default('0');

            /**
             * Postfix Feature
             */
            $table->renameColumn('db_host', 'postfix_db_host');
            $table->renameColumn('db_name', 'postfix_db_name');
            $table->renameColumn('db_user', 'postfix_db_user');
            $table->renameColumn('db_password', 'postfix_db_password');
            $table->renameColumn('db_port', 'postfix_db_port');

            /**
             * SSH Feature
             */
            $table->renameColumn('user', 'ssh_user');
            $table->renameColumn('public_key', 'ssh_public_key');
            $table->renameColumn('private_key', 'ssh_private_key');
            $table->renameColumn('sudo', 'ssh_command_sudo');
            $table->renameColumn('postqueue', 'ssh_command_postqueue');
            $table->renameColumn('postsuper', 'ssh_command_postsuper');

            $table->boolean('ssh_feature_enabled')->default('0');

            /**
             * Amavis feature
             */
            $table->string('amavis_db_host')->nullable();
            $table->string('amavis_db_name')->nullable();
            $table->string('amavis_db_user')->nullable();
            $table->text('amavis_db_password')->nullable();
            $table->string('amavis_db_port')->nullable();

            $table->boolean('amavis_feature_enabled')->default('0');
        });

        \App\Services\Server\Models\Server::all()->each(function($server) {
            $server->log_db_host = $server->postfix_db_host;
            $server->log_db_name = 'Syslog';
            $server->log_db_user = $server->postfix_db_user;
            $server->log_db_password = $server->postfix_db_password;
            $server->log_db_port = $server->postfix_db_port;

            $server->postfix_feature_enabled = true;
            $server->log_feature_enabled = true;
            $server->ssh_feature_enabled = true;

            $server->save();
        });
    }
}

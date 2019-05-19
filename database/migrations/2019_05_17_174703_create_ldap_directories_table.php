<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLdapDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ldap_directories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('connection')->unique();
            $table->string('schema')->default('active_directory');
            $table->string('group_dn')->nullable();
            $table->boolean('password_sync')->default(false);
            $table->string('account_prefix')->nullable();
            $table->string('account_suffix')->nullable();
            $table->string('discover_attr')->nullable();
            $table->string('authenticate_attr')->nullable();
            $table->string('servers');
            $table->string('port')->default(389);
            $table->string('timeout')->default(5);
            $table->string('base_dn');
            $table->string('bind_user');
            $table->text('bind_password');
            $table->boolean('use_ssl')->default(false);
            $table->boolean('use_tls')->default(false);
            $table->string('sso_auth_attr')->nullable();
            $table->text('ignored_domains')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ldap_directories');
    }
}

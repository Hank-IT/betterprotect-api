<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_access', function (Blueprint $table) {
            $table->string('payload',191)->change();
        });

        Schema::table('relay_domains', function (Blueprint $table) {
            $table->string('domain',191)->change();
        });

        Schema::table('relay_recipients', function (Blueprint $table) {
            $table->string('payload', 191)->change();
        });

        Schema::table('sender_access', function (Blueprint $table) {
            $table->string('payload', 191)->change();
        });

        Schema::table('transport_maps', function (Blueprint $table) {
            $table->string('domain', 191)->change();
        });


        Schema::table('client_access', function (Blueprint $table) {
            $table->index('payload');
        });

        Schema::table('relay_domains', function (Blueprint $table) {
            $table->index('domain');
        });

        Schema::table('relay_recipients', function (Blueprint $table) {
            $table->index('payload');
        });

        Schema::table('sender_access', function (Blueprint $table) {
            $table->index('payload');
        });

        Schema::table('transport_maps', function (Blueprint $table) {
            $table->index('domain');
        });
    }
}

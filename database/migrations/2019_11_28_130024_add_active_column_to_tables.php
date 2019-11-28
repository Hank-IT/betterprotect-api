<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveColumnToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_sender_access', function (Blueprint $table) {
            $table->boolean('active')->default('1');
        });

        Schema::table('relay_domains', function (Blueprint $table) {
            $table->boolean('active')->default('1');
        });

        Schema::table('relay_recipients', function (Blueprint $table) {
            $table->boolean('active')->default('1');
        });

        Schema::table('transports', function (Blueprint $table) {
            $table->boolean('active')->default('1');
        });
    }
}

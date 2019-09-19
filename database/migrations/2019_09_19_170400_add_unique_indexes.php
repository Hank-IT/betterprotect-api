<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_sender_access', function (Blueprint $table) {
            $table->string('payload', 255)->unique()->change();
        });

        Schema::table('relay_recipients', function (Blueprint $table) {
            $table->string('payload', 255)->unique()->change();
        });

        Schema::table('transports', function (Blueprint $table) {
            $table->string('domain', 255)->unique()->change();
        });
    }
}

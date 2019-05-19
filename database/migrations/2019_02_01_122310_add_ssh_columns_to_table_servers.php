<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSshColumnsToTableServers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string('user')->nullable();
            $table->text('public_key')->nullable();
            $table->text('private_key')->nullable();
            $table->text('postqueue')->nullable();
            $table->text('postsuper')->nullable();
            $table->text('sudo')->nullable();
        });
    }
}

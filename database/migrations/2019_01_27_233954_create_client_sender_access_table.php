<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientSenderAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_sender_access', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payload',1024);
            $table->string('type');
            $table->string('action');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
}

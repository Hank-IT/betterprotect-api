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
            $table->bigIncrements('id');
            $table->string('client_type');
            $table->string('client_payload', 1024)->nullable();
            $table->string('sender_type');
            $table->string('sender_payload', 1024)->nullable();
            $table->string('action');
            $table->unsignedBigInteger('priority')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_sender_access');
    }
}

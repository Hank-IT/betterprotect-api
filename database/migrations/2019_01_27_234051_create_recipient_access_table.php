<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipient_access', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data_source')->default('local');
            $table->string('action')->default('OK');
            $table->string('payload', 1024);
            $table->timestamps();
        });
    }
}

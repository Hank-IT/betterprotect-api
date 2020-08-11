<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailaddrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailaddr', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('priority')->default('7');
            $table->binary('email')->unique();
        });
    }
}

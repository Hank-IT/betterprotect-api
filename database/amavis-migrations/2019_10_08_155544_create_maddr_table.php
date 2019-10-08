<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaddrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maddr', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partition_tag')->default('0')->unique();
            $table->binary('email')->unique();
            $table->string('domain', 255);
        });
    }
}

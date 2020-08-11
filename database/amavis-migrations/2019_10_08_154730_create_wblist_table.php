<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWblistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wblist', function (Blueprint $table) {
            $table->integer('rid')->primary();
            $table->integer('sid')->primary();
            $table->string('wb', 10);
        });
    }
}

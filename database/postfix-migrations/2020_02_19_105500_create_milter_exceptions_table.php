<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilterExceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milter_exceptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('definition', 1024);
            $table->string('payload', 1024);
        });
    }
}

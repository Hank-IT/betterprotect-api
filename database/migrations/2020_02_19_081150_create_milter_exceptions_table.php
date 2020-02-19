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
            $table->increments('id');
            $table->string('client_type');
            $table->string('client_payload',1024);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
}

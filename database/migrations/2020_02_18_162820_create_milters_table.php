<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('definition');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_maps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain');
            $table->string('payload');
        });
    }
}

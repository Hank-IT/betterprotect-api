<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain');
            $table->string('transport')->nullable();
            $table->string('nexthop')->nullable();
            $table->unsignedSmallInteger('nexthop_port')->default(25);
            $table->string('nexthop_type')->nullable();
            $table->string('nexthop_mx')->nullable();
            $table->timestamps();
        });
    }
}

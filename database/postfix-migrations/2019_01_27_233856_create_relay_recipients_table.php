<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelayRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relay_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payload',1024);
            $table->string('action');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelayDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relay_domains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain');
            $table->timestamps();
        });
    }
}

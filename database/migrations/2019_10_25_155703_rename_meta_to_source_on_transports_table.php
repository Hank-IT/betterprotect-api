<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMetaToSourceOnTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->renameColumn('meta', 'data_source');
        });

        Schema::table('transports', function (Blueprint $table) {
            $table->text('data_source')->default('local')->change();
        });
    }
}

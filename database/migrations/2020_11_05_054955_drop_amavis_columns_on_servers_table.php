<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAmavisColumnsOnServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('amavis_db_host');
            $table->dropColumn('amavis_db_name');
            $table->dropColumn('amavis_db_user');
            $table->dropColumn('amavis_db_password');
            $table->dropColumn('amavis_db_port');
            $table->dropColumn('amavis_feature_enabled');
        });
    }
}

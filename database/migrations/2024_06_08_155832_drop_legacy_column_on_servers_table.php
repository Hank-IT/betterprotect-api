<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('postfix_feature_enabled');
            $table->dropColumn('log_feature_enabled');
            $table->dropColumn('ssh_feature_enabled');
            $table->dropColumn('description');
            $table->dropColumn('active');
        });
    }
};

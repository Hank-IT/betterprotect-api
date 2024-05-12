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
        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('startDate', 'started_at');
            $table->renameColumn('endDate', 'ended_at');
            $table->dropColumn('message');
        });
    }
};

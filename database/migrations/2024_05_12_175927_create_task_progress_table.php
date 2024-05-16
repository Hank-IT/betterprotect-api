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
        Schema::create('task_progresses', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('task_id')->constrained();
            $table->text('description');
            $table->timestamps();
        });
    }
};

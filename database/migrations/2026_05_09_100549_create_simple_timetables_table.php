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
        Schema::create('simple_timetables', function (Blueprint $table) {
            $table->id();
            $table->string('day')->unique();
            $table->string('t_8_00_8_30')->nullable();
            $table->string('t_8_30_9_00')->nullable();
            $table->string('t_9_00_9_30')->nullable();
            $table->string('t_9_30_10_00')->nullable();
            $table->string('t_10_00_10_30')->nullable();
            $table->string('t_10_30_11_00')->nullable();
            $table->string('t_11_00_11_30')->nullable();
            $table->string('t_11_30_12_00')->nullable();
            $table->string('t_12_00_12_30')->nullable();
            $table->string('t_12_30_1_00')->nullable();
            $table->string('t_1_00_1_30')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simple_timetables');
    }
};

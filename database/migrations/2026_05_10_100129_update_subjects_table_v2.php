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
        Schema::table('subjects', function (Blueprint $table) {
            // Check if name exists, we might want to rename it or just add subject_name
            // For simplicity and matching user request exactly:
            if (!Schema::hasColumn('subjects', 'grade')) {
                $table->string('grade')->nullable();
            }
            if (!Schema::hasColumn('subjects', 'subject_name')) {
                $table->string('subject_name')->nullable();
            }
            if (!Schema::hasColumn('subjects', 'pdf')) {
                $table->string('pdf')->nullable();
            }
            if (!Schema::hasColumn('subjects', 'assignment')) {
                $table->text('assignment')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['grade', 'subject_name', 'pdf', 'assignment']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // grade column already exists — just add the composite unique index
        // Safely drop old day unique index if it exists
        $indexes = \DB::select("SHOW INDEX FROM simple_timetables WHERE Key_name = 'simple_timetables_day_unique'");
        if (!empty($indexes)) {
            \DB::statement('ALTER TABLE simple_timetables DROP INDEX simple_timetables_day_unique');
        }
        // Use raw SQL to create composite index with prefix lengths (avoid 1000-byte limit)
        \DB::statement('CREATE UNIQUE INDEX simple_timetables_day_grade_unique ON simple_timetables (day(50), grade(50))');
    }

    public function down(): void
    {
        Schema::table('simple_timetables', function (Blueprint $table) {
            $table->dropUnique(['day', 'grade']);
            $table->dropColumn('grade');
            $table->unique('day');
        });
    }
};

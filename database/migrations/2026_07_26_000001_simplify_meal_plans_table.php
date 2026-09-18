<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meal_plans', function (Blueprint $table) {
            $table->text('meal')->nullable()->after('day');
        });

        DB::table('meal_plans')->get()->each(function ($row) {
            $parts = array_filter([
                $row->breakfast ? 'Breakfast: ' . $row->breakfast : null,
                $row->lunch ? 'Lunch: ' . $row->lunch : null,
                $row->snack ? 'Snack: ' . $row->snack : null,
            ]);

            if (!empty($parts)) {
                DB::table('meal_plans')->where('id', $row->id)->update([
                    'meal' => implode("\n", $parts),
                ]);
            }
        });

        Schema::table('meal_plans', function (Blueprint $table) {
            $table->dropColumn(['breakfast', 'lunch', 'snack']);
        });
    }

    public function down(): void
    {
        Schema::table('meal_plans', function (Blueprint $table) {
            $table->text('breakfast')->nullable();
            $table->text('lunch')->nullable();
            $table->text('snack')->nullable();
            $table->dropColumn('meal');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->string('target_audience', 20)->default('all'); // all, students, teachers, individual
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropForeign(['recipient_id']);
            $table->dropColumn(['target_audience', 'recipient_id']);
        });
    }
};

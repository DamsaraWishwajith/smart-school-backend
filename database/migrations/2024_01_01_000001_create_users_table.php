<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // add additional columns to existing users table instead of recreating it
        Schema::table('users', function (Blueprint $table) {
            // adjust name length if needed (requires doctrine/dbal to change)
            // $table->string('name', 100)->change();
            // additional custom fields
            $table->enum('role', ['admin', 'teacher', 'student', 'parent'])->default('student');
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->date('dob')->nullable();
            $table->string('profile_pic', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address', 'dob', 'profile_pic']);
            // if name length was changed, you could revert it here as well
        });
    }
};

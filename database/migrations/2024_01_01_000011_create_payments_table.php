<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('fee_id')->constrained('fees');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['card', 'bank_transfer', 'cash', 'digital_wallet']);
            $table->string('transaction_id', 100)->unique();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('payment_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};

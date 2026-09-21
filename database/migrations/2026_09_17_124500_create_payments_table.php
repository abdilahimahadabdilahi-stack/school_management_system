<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->onDelete('set null');
            $table->decimal('amount', 10, 2);          // Lacagta la bixiyay
            $table->decimal('total_fee', 10, 2);       // Wadarta lacagta loo baahan yahay
            $table->decimal('balance', 10, 2)->default(0); // Lacagta hadhay
            $table->enum('status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money'])->default('cash');
            $table->string('receipt_number')->nullable();
            $table->date('payment_date');
            $table->string('description')->nullable();  // Sharaxaad
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

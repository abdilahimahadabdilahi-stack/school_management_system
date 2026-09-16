<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Midterm, Final Exam, Quiz 1
            $table->string('subject'); // e.g. Mathematics, English
            $table->date('exam_date');
            $table->time('start_time')->nullable();
            $table->integer('total_marks')->default(100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
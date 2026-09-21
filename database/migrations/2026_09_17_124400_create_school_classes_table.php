<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->integer('class_number'); // 1 ilaa 8
            $table->string('section');       // A ama B
            $table->string('class_label');   // e.g. "1A", "1B", "2A", "2B"
            $table->string('class_teacher')->nullable(); // Macallinka fasalka
            $table->integer('capacity')->default(40);    // Tirada ugu badan ee ardayda
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};

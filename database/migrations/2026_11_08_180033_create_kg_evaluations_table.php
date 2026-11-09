<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kg_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->restrictOnDelete();
            $table->string('report_period', 50);
            $table->string('skill', 100);
            $table->string('rating', 30);
            $table->text('notes')->nullable();
            $table->date('evaluated_on');
            $table->timestamps();

            $table->unique(
                ['student_id', 'school_class_id', 'report_period', 'skill'],
                'kg_evaluations_student_class_period_skill_unique',
            );
            $table->index(['school_class_id', 'report_period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kg_evaluations');
    }
};

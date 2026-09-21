<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('school_classes')
            ->orderBy('id')
            ->each(function (object $schoolClass): void {
                $tableName = $this->tableName($schoolClass->class_label);

                if (! Schema::hasTable($tableName)) {
                    Schema::create($tableName, function (Blueprint $table): void {
                        $table->id();
                        $table->unsignedBigInteger('source_student_id')->nullable();
                        $table->unsignedBigInteger('school_class_id');
                        $table->string('class_label');
                        $table->string('class_teacher')->nullable();
                        $table->unsignedInteger('capacity')->default(40);
                        $table->unsignedBigInteger('parent_id')->nullable();
                        $table->string('student_name');
                        $table->unsignedInteger('student_age');
                        $table->string('student_email');
                        $table->string('section')->nullable();
                        $table->string('subject')->nullable();
                        $table->timestamps();

                        $table->index('source_student_id');
                        $table->index('school_class_id');
                    });
                }

                $students = DB::table('students')
                    ->where('class_name', $schoolClass->class_label)
                    ->get();

                foreach ($students as $student) {
                    DB::table($tableName)->updateOrInsert(
                        ['source_student_id' => $student->id],
                        [
                            'school_class_id' => $schoolClass->id,
                            'class_label' => $schoolClass->class_label,
                            'class_teacher' => $schoolClass->class_teacher,
                            'capacity' => $schoolClass->capacity,
                            'parent_id' => $student->parent_id,
                            'student_name' => $student->name,
                            'student_age' => $student->age,
                            'student_email' => $student->email,
                            'section' => $student->section,
                            'subject' => $student->subject,
                            'created_at' => $student->created_at,
                            'updated_at' => $student->updated_at,
                        ],
                    );
                }
            });
    }

    public function down(): void
    {
        DB::table('school_classes')
            ->orderBy('id')
            ->each(function (object $schoolClass): void {
                Schema::dropIfExists($this->tableName($schoolClass->class_label));
            });
    }

    private function tableName(string $classLabel): string
    {
        return 'class_'.Str::lower(Str::slug($classLabel, '_')).'_students';
    }
};

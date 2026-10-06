<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->foreignId('class_id')
                ->nullable()
                ->after('student_id')
                ->constrained('school_classes')
                ->restrictOnDelete();
            $table->index(['attendance_date', 'class_id'], 'attendances_date_class_index');
        });

        $classIdsByLabel = [];
        $classIdsByNumberAndSection = [];

        DB::table('school_classes')
            ->orderBy('id')
            ->get(['id', 'class_number', 'section', 'class_label'])
            ->each(function (object $schoolClass) use (&$classIdsByLabel, &$classIdsByNumberAndSection): void {
                $classIdsByLabel[Str::lower($schoolClass->class_label)] ??= $schoolClass->id;
                $classIdsByNumberAndSection[
                    $schoolClass->class_number.'|'.Str::lower($schoolClass->section)
                ] ??= $schoolClass->id;
            });

        DB::table('attendances')
            ->join('students', 'students.id', '=', 'attendances.student_id')
            ->select('attendances.id as attendance_id', 'students.class_name', 'students.section')
            ->orderBy('attendances.id')
            ->chunkById(500, function (Collection $attendances) use ($classIdsByLabel, $classIdsByNumberAndSection): void {
                foreach ($attendances as $attendance) {
                    $classId = $classIdsByLabel[Str::lower($attendance->class_name)]
                        ?? $classIdsByNumberAndSection[
                            $attendance->class_name.'|'.Str::lower($attendance->section ?? '')
                        ]
                        ?? null;

                    if ($classId !== null) {
                        DB::table('attendances')
                            ->where('id', $attendance->attendance_id)
                            ->update(['class_id' => $classId]);
                    }
                }
            }, 'attendances.id', 'attendance_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropIndex('attendances_date_class_index');
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
};

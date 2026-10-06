<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('attendances')->whereNull('class_id')->exists()) {
            throw new RuntimeException(
                'Cannot require attendances.class_id: assign a school class to every existing attendance record, then rerun this migration.',
            );
        }

        $duplicateAttendance = DB::table('attendances')
            ->select('student_id', 'class_id', 'attendance_date')
            ->groupBy('student_id', 'class_id', 'attendance_date')
            ->havingRaw('COUNT(*) > 1')
            ->first();

        if ($duplicateAttendance !== null) {
            throw new RuntimeException(
                'Cannot enforce unique class attendance: remove duplicate student, class, and date records, then rerun this migration.',
            );
        }

        Schema::table('attendances', function (Blueprint $table): void {
            $table->unsignedBigInteger('class_id')->nullable(false)->change();
            $table->unique(
                ['student_id', 'class_id', 'attendance_date'],
                'attendances_student_class_date_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropUnique('attendances_student_class_date_unique');
            $table->unsignedBigInteger('class_id')->nullable()->change();
        });
    }
};

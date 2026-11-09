<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_classes', function (Blueprint $table): void {
            $table->string('class_type')->default('primary')->after('class_number');
            $table->index(['class_type', 'class_number', 'section']);
        });

        Schema::table('students', function (Blueprint $table): void {
            $table->foreignId('school_class_id')
                ->nullable()
                ->after('class_name')
                ->constrained('school_classes')
                ->nullOnDelete();
        });

        foreach (range(1, 3) as $level) {
            foreach (['A', 'B', 'C'] as $section) {
                $label = "KG {$level}{$section}";

                DB::table('school_classes')->updateOrInsert(
                    ['class_label' => $label],
                    [
                        'class_number' => $level,
                        'class_type' => 'kindergarten',
                        'section' => $section,
                        'class_teacher' => null,
                        'capacity' => 40,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            }
        }

        DB::table('school_classes')
            ->orderBy('id')
            ->get(['id', 'class_number', 'class_type', 'class_label', 'section'])
            ->each(function (object $schoolClass): void {
                $students = DB::table('students')
                    ->whereNull('school_class_id')
                    ->whereRaw('LOWER(class_name) = ?', [strtolower($schoolClass->class_label)]);

                if ($schoolClass->class_type === 'kindergarten') {
                    $students->orWhere(function ($query) use ($schoolClass): void {
                        $query->where('class_name', 'KG '.$schoolClass->class_number)
                            ->whereRaw('LOWER(section) = ?', [strtolower($schoolClass->section)]);
                    });
                } else {
                    $students->orWhere(function ($query) use ($schoolClass): void {
                        $query->where('class_name', (string) $schoolClass->class_number)
                            ->whereRaw('LOWER(section) = ?', [strtolower($schoolClass->section)]);
                    });
                }

                $students->update(['school_class_id' => $schoolClass->id]);
            });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('school_class_id');
        });

        DB::table('school_classes')
            ->where('class_type', 'kindergarten')
            ->delete();

        Schema::table('school_classes', function (Blueprint $table): void {
            $table->dropIndex(['class_type', 'class_number', 'section']);
            $table->dropColumn('class_type');
        });
    }
};

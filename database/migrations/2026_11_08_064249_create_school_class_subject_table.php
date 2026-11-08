<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_class_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['school_class_id', 'subject_id']);
        });

        foreach ([
            'Somali' => ['Af-Somali', 'af-somali'],
            'Tarbiya (Islamic Studies)' => ['Tarbia (Islamic Studies)', 'tarbia-islamic-studies'],
        ] as $legacyName => [$name, $slug]) {
            $legacySubject = DB::table('subjects')->where('name', $legacyName)->first();

            if ($legacySubject !== null && ! DB::table('subjects')->where('name', $name)->exists()) {
                DB::table('subjects')->where('id', $legacySubject->id)->update([
                    'name' => $name,
                    'slug' => $slug,
                    'updated_at' => now(),
                ]);
                DB::table('exam_results')->where('subject_id', $legacySubject->id)->update(['subject' => $name]);
            }
        }

        $now = now();
        DB::table('subjects')->insertOrIgnore(
            collect([
                'Tarbia (Islamic Studies)',
                'Arabic',
                'Af-Somali',
                'English',
                'Science',
                'Mathematics',
                'Social Studies',
            ])->map(fn (string $name, int $index): array => [
                'name' => $name,
                'slug' => str($name)->slug(),
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all(),
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_class_subject');
    }
};

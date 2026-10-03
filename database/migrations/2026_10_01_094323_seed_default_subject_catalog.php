<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $subjects = [
            'Somali',
            'Tarbiya (Islamic Studies)',
            'Arabic',
            'Social Studies',
            'Maths',
            'Science',
            'English',
            'Physics',
            'Chemistry',
        ];

        $now = now();

        DB::table('subjects')->insertOrIgnore(
            collect($subjects)->map(fn (string $name, int $index): array => [
                'name' => $name,
                'slug' => str($name)->slug(),
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all(),
        );
    }

    public function down(): void
    {
        DB::table('subjects')
            ->whereIn('name', [
                'Somali',
                'Tarbiya (Islamic Studies)',
                'Arabic',
                'Social Studies',
                'Maths',
                'Science',
                'English',
                'Physics',
                'Chemistry',
            ])
            ->delete();
    }
};

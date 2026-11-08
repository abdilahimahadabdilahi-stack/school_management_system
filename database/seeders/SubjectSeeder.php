<?php

namespace Database\Seeders;

use App\Models\ExamResult;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'Tarbia (Islamic Studies)',
            'Arabic',
            'Af-Somali',
            'English',
            'Science',
            'Mathematics',
            'Social Studies',
        ];

        Subject::query()->where('name', 'Maths')->update([
            'name' => 'Mathematics',
            'slug' => 'mathematics',
        ]);

        foreach ($subjects as $sortOrder => $name) {
            $subject = Subject::updateOrCreate(
                ['name' => $name],
                [
                    'slug' => str($name)->slug(),
                    'sort_order' => $sortOrder + 1,
                ],
            );

            ExamResult::query()
                ->whereNull('subject_id')
                ->where('subject', $name)
                ->update(['subject_id' => $subject->id]);
        }
    }
}

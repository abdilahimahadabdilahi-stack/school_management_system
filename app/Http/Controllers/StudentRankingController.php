<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentRankingController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'class_id' => ['nullable', 'integer', Rule::exists('school_classes', 'id')],
        ]);

        $classes = SchoolClass::ordered()->get();
        $selectedClass = isset($validated['class_id'])
            ? SchoolClass::findOrFail((int) $validated['class_id'])
            : null;
        $overallTopStudents = $this->topStudents();
        $classTopStudents = $selectedClass ? $this->topStudents($selectedClass) : collect();

        return view('rankings.index', compact(
            'classes',
            'selectedClass',
            'overallTopStudents',
            'classTopStudents',
        ));
    }

    private function topStudents(?SchoolClass $schoolClass = null): Collection
    {
        $studentPerformance = Student::query()
            ->select(['students.id', 'students.name', 'students.class_name', 'students.section'])
            ->withExamPerformance()
            ->when($schoolClass, function (Builder $query) use ($schoolClass): void {
                $query->whereIn('students.id', $schoolClass->studentsForDisplay()->select('students.id'));
            });

        $rankedStudents = DB::query()
            ->fromSub($studentPerformance, 'student_performance')
            ->select('student_performance.*')
            ->selectRaw('RANK() OVER (ORDER BY (total_obtained * 1.0 / NULLIF(total_possible_marks, 0)) DESC) AS rank')
            ->selectRaw('ROUND((total_obtained * 100.0 / NULLIF(total_possible_marks, 0)), 2) AS percentage');

        return DB::query()
            ->fromSub($rankedStudents, 'ranked_students')
            ->where('rank', '<=', 10)
            ->orderBy('rank')
            ->orderBy('name')
            ->get();
    }
}

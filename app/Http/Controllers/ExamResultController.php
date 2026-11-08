<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExamResultController extends Controller
{
    private const PASS_THRESHOLD = 50;

    public function results(Request $request, Exam $exam): View
    {
        $search = $request->string('search')->trim()->toString();
        $subjects = $this->subjectCatalog();
        /** @var \Illuminate\Database\Eloquent\Collection<int, Student> $students */
        $students = Student::query()
            ->when($search, function ($query, string $search): void {
                $query->where(function ($studentQuery) use ($search): void {
                    $studentQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('id', $search);
                });
            })
            ->orderBy('class_name')
            ->orderBy('section')
            ->orderBy('name')
            ->get();
        $results = $exam->results()->with(['student', 'subjectRecord'])->get();
        $catalog = Subject::query()->orderBy('sort_order')->get();
        $subjectsForTotals = $this->subjectsRecordedForExam($catalog, $results);

        $studentSummaries = $students->mapWithKeys(function (Student $student) use ($exam, $results, $subjectsForTotals): array {
            $studentResults = $results->where('student_id', $student->id);
            $summary = $student->examResultsTotals($exam, $studentResults, $subjectsForTotals);

            return [
                $student->id => [
                    'student' => $student,
                    'results' => $summary['results'],
                    'total_obtained' => $summary['total_obtained'],
                    'total_marks' => $summary['total_marks'],
                    'percentage' => $summary['percentage'],
                    'grade' => $this->gradeForPercentage($summary['percentage']),
                    'status' => $summary['percentage'] >= self::PASS_THRESHOLD ? 'Passed' : 'Failed',
                    'band' => $summary['percentage'] >= self::PASS_THRESHOLD && $summary['percentage'] < 70
                        ? 'Average'
                        : ($summary['percentage'] >= 70 ? 'Above Average' : 'Below Average'),
                ],
            ];
        });

        $filter = $request->string('status')->toString();
        $filteredSummaries = $studentSummaries->filter(
            fn (array $summary): bool => $this->matchesStatusFilter($summary['percentage'], $filter),
        );
        $classAverage = round((float) ($studentSummaries->avg('percentage') ?? 0), 2);
        $passedCount = $studentSummaries->where('percentage', '>=', self::PASS_THRESHOLD)->count();
        $failedCount = $studentSummaries->where('percentage', '<', self::PASS_THRESHOLD)->count();

        return view('exams.results', compact(
            'exam',
            'subjects',
            'students',
            'studentSummaries',
            'filteredSummaries',
            'classAverage',
            'passedCount',
            'failedCount',
            'filter',
            'search',
        ))->with('passThreshold', self::PASS_THRESHOLD);
    }

    public function storeResult(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')->whereIn('slug', array_keys(Subject::CORE_SUBJECTS))],
            'marks_obtained' => ['required', 'numeric', 'min:0', 'lte:total_marks'],
            'total_marks' => ['required', 'numeric', 'gt:0'],
        ]);
        $subject = Subject::findOrFail($validated['subject_id']);

        ExamResult::updateOrCreate(
            [
                'exam_id' => $exam->id,
                'student_id' => $validated['student_id'],
                'subject_id' => $subject->id,
            ],
            [
                'subject' => $subject->name,
                'marks_obtained' => $validated['marks_obtained'],
                'total_marks' => $validated['total_marks'],
            ],
        );

        return redirect()->route('exams.results', $exam)->with('success', 'Exam result saved successfully.');
    }

    public function show(Exam $exam, ExamResult $examResult): View
    {
        $examResult = $exam->results()->with('student')->whereKey($examResult->id)->firstOrFail();

        return view('exams.results.show', compact('exam', 'examResult'));
    }

    public function edit(Exam $exam, ExamResult $examResult): View
    {
        $examResult = $exam->results()->with('student')->whereKey($examResult->id)->firstOrFail();
        $students = Student::query()->orderBy('name')->get(['id', 'name']);
        $subjects = $this->subjectCatalog();

        return view('exams.results.edit', compact('exam', 'examResult', 'students', 'subjects'));
    }

    public function update(Request $request, Exam $exam, ExamResult $examResult): RedirectResponse
    {
        $examResultId = $examResult->getKey();
        $exam->results()->whereKey($examResultId)->firstOrFail();

        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')->whereIn('slug', array_keys(Subject::CORE_SUBJECTS))],
            'marks_obtained' => ['required', 'numeric', 'min:0', 'lte:total_marks'],
            'total_marks' => ['required', 'numeric', 'gt:0'],
        ]);
        $subject = Subject::findOrFail($validated['subject_id']);

        DB::table('exam_results')
            ->where('exam_id', $exam->getKey())
            ->where('id', $examResultId)
            ->update([
                'student_id' => $validated['student_id'],
                'subject_id' => $subject->getKey(),
                'subject' => $subject->name,
                'marks_obtained' => $validated['marks_obtained'],
                'total_marks' => $validated['total_marks'],
            ]);

        return redirect()
            ->route('exams.results', $exam)
            ->with('success', 'Exam result updated successfully.');
    }

    public function destroy(Exam $exam, ExamResult $examResult): RedirectResponse
    {
        $examResultId = $examResult->getKey();
        $exam->results()->whereKey($examResultId)->firstOrFail();
        DB::table('exam_results')
            ->where('exam_id', $exam->getKey())
            ->where('id', $examResultId)
            ->delete();

        return redirect()
            ->route('exams.results', $exam)
            ->with('success', 'Exam result deleted successfully.');
    }

    private function matchesStatusFilter(float $percentage, string $filter): bool
    {
        return match ($filter) {
            'passed' => $percentage >= self::PASS_THRESHOLD,
            'failed' => $percentage < self::PASS_THRESHOLD,
            'average' => $percentage >= self::PASS_THRESHOLD && $percentage < 70,
            default => true,
        };
    }

    /**
     * Ensure older installations with an empty subject table can record results.
     */
    private function subjectCatalog(): Collection
    {
        $subjects = Subject::core()->orderBy('sort_order')->get();

        if ($subjects->isNotEmpty()) {
            return $subjects;
        }

        foreach (Subject::CORE_SUBJECTS as $slug => $name) {
            Subject::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'slug' => str($name)->slug(),
                    'sort_order' => array_search($slug, array_keys(Subject::CORE_SUBJECTS), true) + 1,
                ],
            );
        }

        return Subject::core()->orderBy('sort_order')->get();
    }

    private function subjectsRecordedForExam(Collection $catalog, Collection $results): Collection
    {
        $recordedSubjects = $catalog->filter(function (Subject $subject) use ($results): bool {
            return $results->contains(
                fn (ExamResult $result): bool => (int) $result->subject_id === (int) $subject->id
                    || $result->subject_id === null && $result->subject === $subject->name,
            );
        })->values();

        return $recordedSubjects->isNotEmpty() ? $recordedSubjects : $catalog;
    }

    private function gradeForPercentage(float $percentage): string
    {
        return match (true) {
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= self::PASS_THRESHOLD => 'D',
            default => 'F',
        };
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    private const PASS_THRESHOLD = 50;

    public function results(Request $request, Exam $exam): View
    {
        $search = $request->string('search')->trim()->toString();
        $subjects = Subject::query()->orderBy('sort_order')->get();
        $students = Student::query()
            ->when($search, function ($query, string $search): void {
                $query->where(function ($studentQuery) use ($search): void {
                    $studentQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('id', $search);
                });
            })
            ->orderBy('name')
            ->get();
        $results = $exam->results()->with(['student', 'subjectRecord'])->get();

        $studentSummaries = $students->mapWithKeys(function (Student $student) use ($exam, $results, $subjects): array {
            $studentResults = $results->where('student_id', $student->id);
            $subjectRows = $subjects->map(function (Subject $subject) use ($exam, $studentResults): array {
                $result = $studentResults->first(
                    fn (ExamResult $examResult): bool => $examResult->subject_id === $subject->id
                        || ($examResult->subject_id === null && $examResult->subject === $subject->name),
                );
                $totalMarks = (float) ($result?->total_marks ?? $exam->total_marks);
                $marksObtained = (float) ($result?->marks_obtained ?? 0);
                $percentage = $totalMarks > 0 ? round(($marksObtained / $totalMarks) * 100, 2) : 0.0;

                return [
                    'record' => $result,
                    'subject' => $subject,
                    'marks_obtained' => $marksObtained,
                    'total_marks' => $totalMarks,
                    'percentage' => $percentage,
                    'grade' => $this->gradeForPercentage($percentage),
                    'status' => $percentage >= self::PASS_THRESHOLD ? 'Passed' : 'Failed',
                ];
            });
            $totalObtained = (float) $subjectRows->sum('marks_obtained');
            $totalMarks = (float) $subjectRows->sum('total_marks');
            $percentage = $totalMarks > 0 ? round(($totalObtained / $totalMarks) * 100, 2) : 0.0;

            return [
                $student->id => [
                    'student' => $student,
                    'results' => $subjectRows,
                    'total_obtained' => $totalObtained,
                    'total_marks' => $totalMarks,
                    'percentage' => $percentage,
                    'grade' => $this->gradeForPercentage($percentage),
                    'status' => $percentage >= self::PASS_THRESHOLD ? 'Passed' : 'Failed',
                    'band' => $percentage >= self::PASS_THRESHOLD && $percentage < 70
                        ? 'Average'
                        : ($percentage >= 70 ? 'Above Average' : 'Below Average'),
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
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
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
        $examResult = $exam->results()->with('student')->findOrFail($examResult->id);

        return view('exams.results.show', compact('exam', 'examResult'));
    }

    public function edit(Exam $exam, ExamResult $examResult): View
    {
        $examResult = $exam->results()->with('student')->findOrFail($examResult->id);
        $students = Student::query()->orderBy('name')->get(['id', 'name']);
        $subjects = Subject::query()->orderBy('sort_order')->get();

        return view('exams.results.edit', compact('exam', 'examResult', 'students', 'subjects'));
    }

    public function update(Request $request, Exam $exam, ExamResult $examResult): RedirectResponse
    {
        $examResult = $exam->results()->findOrFail($examResult->id);

        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'marks_obtained' => ['required', 'numeric', 'min:0', 'lte:total_marks'],
            'total_marks' => ['required', 'numeric', 'gt:0'],
        ]);
        $subject = Subject::findOrFail($validated['subject_id']);

        $examResult->update([
            'student_id' => $validated['student_id'],
            'subject_id' => $subject->id,
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
        $examResult = $exam->results()->findOrFail($examResult->id);
        $examResult->delete();

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

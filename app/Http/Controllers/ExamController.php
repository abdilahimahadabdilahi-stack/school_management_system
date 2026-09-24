<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ExamController extends Controller
{
    private const PASS_THRESHOLD = 50;

    // 1. Muuji dhammaan imtixaanaadka
    public function index(Request $request)
    {
        $search = $request->input('search');

        $exams = Exam::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('exam_date', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('exams.index', compact('exams', 'search'));
    }

    // 2. Foomka lagu daro imtixaan cusub
    public function create()
    {
        return view('exams.create');
    }

    // 3. Kaydi imtixaanka cusub
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'start_time' => 'nullable',
            'total_marks' => 'required|integer|min:1',
        ]);

        Exam::create($validated);

        return redirect()->route('exams.index')->with('success', 'Exam created successfully!');
    }

    // 4. Muuji xogta imtixaan gaar ah (Show)
    public function show(Exam $exam)
    {
        return view('exams.show', compact('exam'));
    }

    public function results(Request $request, Exam $exam): View
    {
        $results = $exam->results()
            ->with('student')
            ->orderBy('subject')
            ->get();

        $studentSummaries = $results
            ->groupBy('student_id')
            ->map(fn (Collection $studentResults): array => $this->summarizeStudentResults($studentResults));

        $filter = $request->string('status')->toString();
        $filteredSummaries = $studentSummaries->filter(
            fn (array $summary): bool => $this->matchesStatusFilter($summary['percentage'], $filter)
        );

        $students = Student::query()->orderBy('name')->get(['id', 'name']);
        $classAverage = round((float) ($studentSummaries->avg('percentage') ?? 0), 2);
        $passedCount = $studentSummaries->filter(
            fn (array $summary): bool => $summary['percentage'] >= self::PASS_THRESHOLD
        )->count();
        $failedCount = $studentSummaries->filter(
            fn (array $summary): bool => $summary['percentage'] < self::PASS_THRESHOLD
        )->count();

        return view('exams.results', compact(
            'exam',
            'results',
            'studentSummaries',
            'filteredSummaries',
            'students',
            'classAverage',
            'passedCount',
            'failedCount',
            'filter',
        ))->with('passThreshold', self::PASS_THRESHOLD);
    }

    public function storeResult(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'subject' => ['required', 'string', 'max:255'],
            'marks_obtained' => ['required', 'numeric', 'min:0', 'lte:total_marks'],
            'total_marks' => ['required', 'numeric', 'gt:0'],
        ]);

        ExamResult::updateOrCreate(
            [
                'exam_id' => $exam->id,
                'student_id' => $validated['student_id'],
                'subject' => $validated['subject'],
            ],
            [
                'marks_obtained' => $validated['marks_obtained'],
                'total_marks' => $validated['total_marks'],
            ],
        );

        return redirect()
            ->route('exams.results', $exam)
            ->with('success', 'Exam result saved successfully.');
    }

    // 5. Foomka wax ka beddelka (Edit)
    public function edit(Exam $exam)
    {
        return view('exams.edit', compact('exam'));
    }

    // 6. Cuddaysii/Update imtixaanka
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'start_time' => 'nullable',
            'total_marks' => 'required|integer|min:1',
        ]);

        $exam->update($validated);

        return redirect()->route('exams.index')->with('success', 'Exam updated successfully!');
    }

    // 7. Tirtir imtixaanka
    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully!');
    }

    /**
     * @return array{student: Student, results: Collection<int, ExamResult>, total_obtained: float, total_marks: float, percentage: float, grade: string, status: string, band: string}
     */
    private function summarizeStudentResults(Collection $studentResults): array
    {
        $totalObtained = (float) $studentResults->sum('marks_obtained');
        $totalMarks = (float) $studentResults->sum('total_marks');
        $percentage = $totalMarks > 0 ? round(($totalObtained / $totalMarks) * 100, 2) : 0.0;

        return [
            'student' => $studentResults->first()->student,
            'results' => $studentResults,
            'total_obtained' => $totalObtained,
            'total_marks' => $totalMarks,
            'percentage' => $percentage,
            'grade' => $this->gradeForPercentage($percentage),
            'status' => $percentage >= self::PASS_THRESHOLD ? 'Passed' : 'Failed',
            'band' => $percentage >= self::PASS_THRESHOLD && $percentage < 70 ? 'Average' : ($percentage >= 70 ? 'Above Average' : 'Below Average'),
        ];
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

<?php

namespace App\Http\Controllers;

use App\Models\KgEvaluation;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class KgEvaluationController extends Controller
{
    private const RATINGS = [
        'Excellent',
        'Satisfactory',
        'Developing',
        'Needs Improvement',
    ];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'class_id' => [
                'nullable',
                'integer',
                Rule::exists('school_classes', 'id')->where('class_type', 'kindergarten'),
            ],
            'report_period' => ['nullable', 'string', 'max:50'],
        ]);
        $classes = SchoolClass::query()->where('class_type', 'kindergarten')->ordered()->get();
        $schoolClass = isset($validated['class_id'])
            ? $classes->firstWhere('id', $validated['class_id'])
            : null;
        $evaluations = $schoolClass
            ? $schoolClass->kgEvaluations()
                ->with('student')
                ->when(
                    ($validated['report_period'] ?? '') !== '',
                    fn ($query) => $query->where('report_period', $validated['report_period']),
                )
                ->orderBy('student_id')
                ->orderBy('skill')
                ->get()
            : collect();
        $reportPeriod = $validated['report_period'] ?? '';

        return view('kg-evaluations.index', compact('classes', 'schoolClass', 'evaluations', 'reportPeriod'));
    }

    public function create(Request $request): View
    {
        $validated = $request->validate([
            'class_id' => [
                'nullable',
                'integer',
                Rule::exists('school_classes', 'id')->where('class_type', 'kindergarten'),
            ],
        ]);
        $classes = SchoolClass::query()->where('class_type', 'kindergarten')->ordered()->get();
        $schoolClass = isset($validated['class_id'])
            ? $classes->firstWhere('id', $validated['class_id'])
            : null;
        $students = $schoolClass
            ? $schoolClass->studentsForDisplay()->orderBy('name')->get()
            : collect();
        $ratings = self::RATINGS;

        return view('kg-evaluations.create', compact('classes', 'schoolClass', 'students', 'ratings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => [
                'required',
                'integer',
                Rule::exists('school_classes', 'id')->where('class_type', 'kindergarten'),
            ],
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'report_period' => ['required', 'string', 'max:50'],
            'skill' => ['required', 'string', 'max:100'],
            'rating' => ['required', Rule::in(self::RATINGS)],
            'evaluated_on' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $schoolClass = SchoolClass::query()
            ->where('class_type', 'kindergarten')
            ->findOrFail($validated['class_id']);
        $student = $schoolClass->studentsForDisplay()->whereKey($validated['student_id'])->first();

        if (! $student instanceof Student) {
            throw ValidationException::withMessages([
                'student_id' => 'Select a student enrolled in the selected kindergarten class.',
            ]);
        }

        KgEvaluation::updateOrCreate(
            [
                'student_id' => $student->id,
                'school_class_id' => $schoolClass->id,
                'report_period' => $validated['report_period'],
                'skill' => $validated['skill'],
            ],
            [
                'rating' => $validated['rating'],
                'evaluated_on' => $validated['evaluated_on'],
                'notes' => $validated['notes'] ?? null,
            ],
        );

        return redirect()->route('kg-evaluations.index', [
            'class_id' => $schoolClass->id,
            'report_period' => $validated['report_period'],
        ])->with('success', 'Kindergarten progress evaluation saved.');
    }
}

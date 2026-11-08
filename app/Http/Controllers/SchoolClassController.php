<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    // 1. Tusi dhammaan fasallada
    public function index()
    {
        $classes = SchoolClass::query()
            ->orderBy('class_number')
            ->orderBy('section')
            ->get();
        $classes->each(function (SchoolClass $schoolClass): void {
            $schoolClass->setAttribute('students_count', $schoolClass->studentsForDisplay()->count());
        });

        return view('classes.index', compact('classes'));
    }

    // 2. Tusi form-ka fasalka cusub
    public function create()
    {
        $subjects = Subject::core()->orderBy('sort_order')->get();

        return view('classes.create', compact('subjects'));
    }

    // 3. Keydi fasalka cusub
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_number' => 'required|integer|min:1|max:8',
            'section' => 'required|string|max:50',
            'class_teacher' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1|max:100',
            'subject_ids' => ['sometimes', 'array'],
            'subject_ids.*' => ['integer', 'distinct', Rule::exists('subjects', 'id')->whereIn('slug', array_keys(Subject::CORE_SUBJECTS))],
        ]);

        $section = trim($validated['section']);
        $classLabel = $validated['class_number'].$section;

        // Hubi in fasalkan horey loo diiwaan geliyay
        $exists = SchoolClass::where('class_label', $classLabel)->exists();
        if ($exists) {
            return back()->withErrors(['class_label' => 'Fasalkan "'.$classLabel.'" horey ayaa loo diiwaan geliyay!'])->withInput();
        }

        $schoolClass = SchoolClass::create([
            'class_number' => $validated['class_number'],
            'section' => $section,
            'class_label' => $classLabel,
            'class_teacher' => $validated['class_teacher'] ?? null,
            'capacity' => $validated['capacity'] ?? 40,
        ]);
        $schoolClass->subjects()->sync($validated['subject_ids'] ?? []);

        return redirect()->route('classes.index')->with('success', 'Fasalka waa la keydiyay!');
    }

    // 4. Tusi faahfaahinta fasalka
    public function show(int $id): View
    {
        $schoolClass = SchoolClass::findOrFail($id);
        $schoolClass->setRelation(
            'students',
            $schoolClass->studentsForDisplay()->with('parent')->orderBy('name')->get(),
        );

        return view('classes.show', compact('schoolClass'));
    }

    // 5. Tusi form-ka xogta lagu beddelo
    public function edit(int $id): View
    {
        $schoolClass = SchoolClass::findOrFail($id);
        $subjects = Subject::core()->orderBy('sort_order')->get();
        $selectedSubjectIds = $schoolClass->subjects()->pluck('subjects.id')->all();

        return view('classes.edit', compact('schoolClass', 'subjects', 'selectedSubjectIds'));
    }

    // 6. Cusbooneysii xogta fasalka
    public function update(Request $request, int $id): RedirectResponse
    {
        $schoolClass = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'class_number' => 'required|integer|min:1|max:8',
            'section' => 'required|string|max:50',
            'class_teacher' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1|max:100',
            'subject_ids' => ['sometimes', 'array'],
            'subject_ids.*' => ['integer', 'distinct', Rule::exists('subjects', 'id')->whereIn('slug', array_keys(Subject::CORE_SUBJECTS))],
        ]);

        $section = trim($validated['section']);
        $classLabel = $validated['class_number'].$section;

        // Hubi in fasalkan horey loo diiwaan geliyay (iskaga reeb tan la wax ka beddelo)
        $exists = SchoolClass::where('class_label', $classLabel)->where('id', '!=', $id)->exists();
        if ($exists) {
            return back()->withErrors(['class_label' => 'Fasalkan "'.$classLabel.'" horey ayaa loo diiwaan geliyay!'])->withInput();
        }

        $schoolClass->update([
            'class_number' => $validated['class_number'],
            'section' => $section,
            'class_label' => $classLabel,
            'class_teacher' => $validated['class_teacher'] ?? null,
            'capacity' => $validated['capacity'] ?? 40,
        ]);
        $schoolClass->subjects()->sync($validated['subject_ids'] ?? []);

        return redirect()->route('classes.index')->with('success', 'Xogta fasalka waa la cusbooneysiiyay!');
    }

    // 7. Tirtir fasalka
    public function destroy(int $id): RedirectResponse
    {
        $schoolClass = SchoolClass::findOrFail($id);
        $schoolClass->delete();

        return redirect()->route('classes.index')->with('success', 'Fasalka waa la tirtiray!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\SchoolParent;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // 1. Tusi dhammaan ardayda
    public function index(Request $request)
    {
        $search = $request->input('search');

        $students = Student::with('parent')
            ->when($search, function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('class_name', 'like', "%{$search}%")
                        ->orWhere('section', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return view('students.index', compact('students', 'search'));
    }

    // 2. Tusi form-ka ardayga cusub lagu daro
    public function create(Request $request)
    {
        $selectedClassId = $request->integer('school_class_id');

        if ($selectedClassId === 0 && $request->filled('class_name')) {
            $selectedClass = SchoolClass::query()
                ->whereRaw('LOWER(class_label) = ?', [strtolower((string) $request->input('class_name'))])
                ->when(
                    $request->filled('section'),
                    fn ($query) => $query->whereRaw('LOWER(section) = ?', [strtolower((string) $request->input('section'))]),
                )
                ->first();
            $selectedClassId = $selectedClass?->id ?? 0;
        }

        return view('students.create', [
            'parents' => SchoolParent::orderBy('name')->get(),
            'classes' => SchoolClass::ordered()->get(),
            'selectedClassId' => $selectedClassId ?: null,
            'subjects' => Subject::CORE_SUBJECTS,
        ]);
    }

    // 3. Keydi ardayga cusub
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'email' => 'nullable|email|unique:students,email',
            'school_class_id' => ['nullable', 'integer', 'exists:school_classes,id'],
            'class_name' => ['required_without:school_class_id', 'nullable', 'string', 'max:255'],
            'section' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'subject_selection_present' => ['sometimes', 'accepted'],
            'subjects' => ['sometimes', 'array'],
            'subjects.*' => ['string', 'distinct', Rule::in(array_values(Subject::CORE_SUBJECTS))],
            'parent_id' => 'nullable|exists:parents,id',
        ]);

        Student::create($this->studentAttributes($validated));

        return $this->redirectAfterChange($request)->with('success', 'Student added successfully!');
    }

    // 4. Tusi faahfaahinta hal arday (Detail view)
    public function show($id)
    {
        $student = Student::findOrFail($id);

        return view('students.show', compact('student'));
    }

    // 5. Tusi form-ka xogta lagu beddelo (Edit view)
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $parents = SchoolParent::orderBy('name')->get();
        $classes = SchoolClass::ordered()->get();

        $selectedSubjectNames = $student->subject === null
            ? []
            : array_map('trim', explode(',', $student->subject));

        return view('students.edit', [
            'student' => $student,
            'parents' => $parents,
            'classes' => $classes,
            'subjects' => Subject::CORE_SUBJECTS,
            'selectedSubjectNames' => $selectedSubjectNames,
        ]);
    }

    // 6. Cusbooneysii (Update) xogta ardayga
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'email' => 'nullable|email|unique:students,email,'.$id,
            'school_class_id' => ['nullable', 'integer', 'exists:school_classes,id'],
            'class_name' => ['required_without:school_class_id', 'nullable', 'string', 'max:255'],
            'section' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'subject_selection_present' => ['sometimes', 'accepted'],
            'subjects' => ['sometimes', 'array'],
            'subjects.*' => ['string', 'distinct', Rule::in(array_values(Subject::CORE_SUBJECTS))],
            'parent_id' => 'nullable|exists:parents,id',
        ]);

        $student->update($this->studentAttributes($validated));

        return $this->redirectAfterChange($request)->with('success', 'Xogta ardayga waa la cusbooneysiiyay!');
    }

    // 7. Tirtir ardayga (Delete)
    public function destroy(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return $this->redirectAfterChange($request)->with('success', 'Ardayga waa la tirtiray!');
    }

    private function redirectAfterChange(Request $request): RedirectResponse
    {
        if ($request->input('return_to') === 'unpaid') {
            $search = $request->input('search');
            $query = [];

            if (is_string($search) && trim($search) !== '') {
                $query['search'] = trim($search);
            }

            $paymentDate = $request->input('payment_date');

            if (is_string($paymentDate) && trim($paymentDate) !== '') {
                $query['payment_date'] = trim($paymentDate);
            }

            return redirect()->route('students.unpaid', $query);
        }

        return redirect()->route('students.index');
    }

    /**
     * Keep the legacy class label and section synchronized with the normalized class relation.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function studentAttributes(array $validated): array
    {
        $schoolClass = isset($validated['school_class_id'])
            ? SchoolClass::findOrFail($validated['school_class_id'])
            : (isset($validated['section']) && $validated['section'] !== ''
                ? SchoolClass::query()
                    ->whereRaw('LOWER(class_label) = ?', [strtolower((string) ($validated['class_name'] ?? ''))])
                    ->whereRaw('LOWER(section) = ?', [strtolower($validated['section'])])
                    ->first()
                : null);

        $attributes = array_merge($validated, [
            'school_class_id' => $schoolClass?->id,
            'class_name' => $schoolClass?->class_label ?? ($validated['class_name'] ?? ''),
            'section' => $schoolClass?->section ?? ($validated['section'] ?? null),
        ]);

        if (array_key_exists('subject_selection_present', $validated)) {
            $subjects = $validated['subjects'] ?? [];
            $attributes['subject'] = $subjects === [] ? null : implode(', ', $subjects);
            unset($attributes['subject_selection_present'], $attributes['subjects']);
        }

        return $attributes;
    }
}

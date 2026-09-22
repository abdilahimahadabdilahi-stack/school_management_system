<?php

namespace App\Http\Controllers;

use App\Models\SchoolParent;
use App\Models\Student;
use Illuminate\Http\Request;

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
    public function create()
    {
        return view('students.create', ['parents' => SchoolParent::orderBy('name')->get()]);
    }

    // 3. Keydi ardayga cusub
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'email' => 'required|email|unique:students,email',
            'class_name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:parents,id',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student added successfully!');
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

        return view('students.edit', compact('student', 'parents'));
    }

    // 6. Cusbooneysii (Update) xogta ardayga
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'email' => 'required|email|unique:students,email,'.$id,
            'class_name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:parents,id',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Xogta ardayga waa la cusbooneysiiyay!');
    }

    // 7. Tirtir ardayga (Delete)
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Ardayga waa la tirtiray!');
    }
}

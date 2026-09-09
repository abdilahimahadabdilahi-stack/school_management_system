<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // 1. Tusi dhammaan ardayda
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    // 2. Tusi form-ka ardayga cusub lagu daro
    public function create()
    {
        return view('students.create');
    }

    // 3. Keydi ardayga cusub
    public function store(Request $request)
{
    $request->validate([
        'name'       => 'required|string|max:255',
        'age'        => 'required|integer',
        'email'      => 'required|email|unique:students,email',
        'class_name' => 'required|string|max:255',
        'subject'    => 'nullable|string|max:255',
    ]);

    Student::create($request->all());

    return redirect()->route('students.index')->with('success', 'Student added successfully!');


        Student::create([
            'name'       => $request->name,
            'age'        => $request->age,
            'email'      => $request->email,
            'class_name' => $request->class,
        ]);

        return redirect()->route('students.index')->with('success', 'Ardayga waa la keydiyay!');
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
        return view('students.edit', compact('student'));
    }

    // 6. Cusbooneysii (Update) xogta ardayga
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'age'   => 'required|integer',
            'email' => 'required|email|unique:students,email,'.$id,
            'class' => 'required|string|max:255',
        ]);

        $student->update([
            'name'       => $request->name,
            'age'        => $request->age,
            'email'      => $request->email,
            'class_name' => $request->class,
        ]);

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
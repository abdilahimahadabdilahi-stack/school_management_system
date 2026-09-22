<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $teachers = Teacher::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return view('teachers.index', compact('teachers', 'search'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:teachers,email',
            'phone'   => 'required|string|max:20',
            'subject' => 'required|string|max:255',
        ]);

        Teacher::create($request->all());

        return redirect()->route('teachers.index')->with('success', 'Macallinka waa la keydiyay!');
    }

    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.show', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:teachers,email,'.$id,
            'phone'   => 'required|string|max:20',
            'subject' => 'required|string|max:255',
        ]);

        $teacher->update($request->all());

        return redirect()->route('teachers.index')->with('success', 'Xogta macallinka waa la cusbooneysiiyay!');
    }

    public function destroy($id)
    { ;;
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Macallinka waa la tirtiray!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
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
            'name'        => 'required|string|max:255',
            'subject'     => 'required|string|max:255',
            'exam_date'   => 'required|date',
            'start_time'  => 'nullable',
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

    // 5. Foomka wax ka beddelka (Edit)
    public function edit(Exam $exam)
    {
        return view('exams.edit', compact('exam'));
    }

    // 6. Cuddaysii/Update imtixaanka
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'subject'     => 'required|string|max:255',
            'exam_date'   => 'required|date',
            'start_time'  => 'nullable',
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
}
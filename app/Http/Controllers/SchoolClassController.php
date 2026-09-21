<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

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
        return view('classes.create');
    }

    // 3. Keydi fasalka cusub
    public function store(Request $request)
    {
        $request->validate([
            'class_number' => 'required|integer|min:1|max:8',
            'section' => 'required|string|in:A,B',
            'class_teacher' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1|max:100',
        ]);

        $classLabel = $request->class_number.$request->section;

        // Hubi in fasalkan horey loo diiwaan geliyay
        $exists = SchoolClass::where('class_label', $classLabel)->exists();
        if ($exists) {
            return back()->withErrors(['class_label' => 'Fasalkan "'.$classLabel.'" horey ayaa loo diiwaan geliyay!'])->withInput();
        }

        SchoolClass::create([
            'class_number' => $request->class_number,
            'section' => $request->section,
            'class_label' => $classLabel,
            'class_teacher' => $request->class_teacher,
            'capacity' => $request->capacity ?? 40,
        ]);

        return redirect()->route('classes.index')->with('success', 'Fasalka waa la keydiyay!');
    }

    // 4. Tusi faahfaahinta fasalka
    public function show($id)
    {
        $schoolClass = SchoolClass::findOrFail($id);
        $schoolClass->setRelation(
            'students',
            $schoolClass->studentsForDisplay()->with('parent')->orderBy('name')->get(),
        );

        return view('classes.show', compact('schoolClass'));
    }

    // 5. Tusi form-ka xogta lagu beddelo
    public function edit($id)
    {
        $schoolClass = SchoolClass::findOrFail($id);

        return view('classes.edit', compact('schoolClass'));
    }

    // 6. Cusbooneysii xogta fasalka
    public function update(Request $request, $id)
    {
        $schoolClass = SchoolClass::findOrFail($id);

        $request->validate([
            'class_number' => 'required|integer|min:1|max:8',
            'section' => 'required|string|in:A,B',
            'class_teacher' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1|max:100',
        ]);

        $classLabel = $request->class_number.$request->section;

        // Hubi in fasalkan horey loo diiwaan geliyay (iskaga reeb tan la wax ka beddelo)
        $exists = SchoolClass::where('class_label', $classLabel)->where('id', '!=', $id)->exists();
        if ($exists) {
            return back()->withErrors(['class_label' => 'Fasalkan "'.$classLabel.'" horey ayaa loo diiwaan geliyay!'])->withInput();
        }

        $schoolClass->update([
            'class_number' => $request->class_number,
            'section' => $request->section,
            'class_label' => $classLabel,
            'class_teacher' => $request->class_teacher,
            'capacity' => $request->capacity ?? 40,
        ]);

        return redirect()->route('classes.index')->with('success', 'Xogta fasalka waa la cusbooneysiiyay!');
    }

    // 7. Tirtir fasalka
    public function destroy($id)
    {
        $schoolClass = SchoolClass::findOrFail($id);
        $schoolClass->delete();

        return redirect()->route('classes.index')->with('success', 'Fasalka waa la tirtiray!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::all();
        return view('staff.index', compact('staffs'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'required|string|max:20',
            'role'  => 'required|string|max:255',
        ]);

        Staff::create($request->all());

        return redirect()->route('staff.index')->with('success', 'Shaqaalaha cusub waa la keydiyay!');
    }

    public function show($id)
    {
        $staff = Staff::findOrFail($id);
        return view('staff.show', compact('staff'));
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,'.$id,
            'phone' => 'required|string|max:20',
            'role'  => 'required|string|max:255',
        ]);

        $staff->update($request->all());

        return redirect()->route('staff.index')->with('success', 'Xogta shaqaalaha waa la cusbooneysiiyay!');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Shaqaalaha waa la tirtiray!');
    }
}
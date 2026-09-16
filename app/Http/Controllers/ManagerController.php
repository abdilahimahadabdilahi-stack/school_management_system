<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Ku muuji dhammaan maamulayaasha liiska (Index page).
     */
    public function index()
    {
        $managers = Manager::latest()->paginate(10);
        return view('managers.index', compact('managers'));
    }

    /**
     * Soo bandhig foomka lagu abuurayo maamule cusub (Create page).
     */
    public function create()
    {
        return view('managers.create');
    }

    /**
     * Ku kaydi maamulaha cusub database-ka (Store action).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:managers,email',
            'phone'       => 'nullable|string|max:20',
            'department'  => 'nullable|string|max:255',
        ]);

        Manager::create($request->all());

        return redirect()->route('managers.index')
                         ->with('success', 'Manager-ka cusub waa lagu daray si guul leh!');
    }

    /**
     * Soo bandhig faahfaahinta hal maamule (Show page).
     */
    public function show(Manager $manager)
    {
        return view('managers.show', compact('manager'));
    }

    /**
     * Soo bandhig foomka wax ka badalka maamule (Edit page).
     */
    public function edit(Manager $manager)
    {
        return view('managers.edit', compact('manager'));
    }

    /**
     * Ku cusboonaysii xogta maamulaha database-ka (Update action).
     */
    public function update(Request $request, Manager $manager)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:managers,email,' . $manager->id,
            'phone'       => 'nullable|string|max:20',
            'department'  => 'nullable|string|max:255',
        ]);

        $manager->update($request->all());

        return redirect()->route('managers.index')
                         ->with('success', 'Xogta maamulaha waa la cusboonaysiiyay!');
    }

    /**
     * Ka tiri maamulaha database-ka (Destroy action).
     */
    public function destroy(Manager $manager)
    {
        $manager->delete();

        return redirect()->route('managers.index')
                         ->with('success', 'Manager-ka waa la tiray si guul leh!');
    }
}
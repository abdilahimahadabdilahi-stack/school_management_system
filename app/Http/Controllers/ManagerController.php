<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Models\Manager;
use App\Models\SecurityLog;
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
    public function store(StoreManagerRequest $request)
    {
        $manager = Manager::create($request->validated());

        try {
            SecurityLog::create([
                'user_id' => auth()->id(),
                'event_type' => 'CREATE_MANAGER',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => "Created manager record ID #{$manager->id} ('{$manager->name}')",
            ]);
        } catch (\Throwable $e) {
            // Silence log errors
        }

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
    public function update(UpdateManagerRequest $request, Manager $manager)
    {
        $manager->update($request->validated());

        try {
            SecurityLog::create([
                'user_id' => auth()->id(),
                'event_type' => 'UPDATE_MANAGER',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => "Updated manager record ID #{$manager->id} ('{$manager->name}')",
            ]);
        } catch (\Throwable $e) {
            // Silence log errors
        }

        return redirect()->route('managers.index')
            ->with('success', 'Xogta maamulaha waa la cusboonaysiiyay!');
    }

    /**
     * Ka tiri maamulaha database-ka (Destroy action).
     */
    public function destroy(Request $request, Manager $manager)
    {
        $managerId = $manager->id;
        $managerName = $manager->name;

        $manager->delete();

        try {
            SecurityLog::create([
                'user_id' => auth()->id(),
                'event_type' => 'DELETE_MANAGER',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => "Deleted manager record ID #{$managerId} ('{$managerName}')",
            ]);
        } catch (\Throwable $e) {
            // Silence log errors
        }

        return redirect()->route('managers.index')
            ->with('success', 'Manager-ka waa la tiray si guul leh!');
    }
}

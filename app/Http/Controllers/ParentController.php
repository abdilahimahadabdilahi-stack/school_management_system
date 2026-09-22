<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Models\SchoolParent;
use App\Models\SecurityLog;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Ku muuji dhammaan waalidiinta liiska (Index page).
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $parents = SchoolParent::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('parents.index', compact('parents', 'search'));
    }

    /**
     * Soo bandhig foomka lagu abuurayo waalid cusub (Create page).
     */
    public function create()
    {
        return view('parents.create');
    }

    /**
     * Ku kaydi waalidka cusub database-ka (Store action).
     */
    public function store(StoreParentRequest $request)
    {
        $parent = SchoolParent::create($request->validated());

        try {
            SecurityLog::create([
                'user_id' => auth()->id(),
                'event_type' => 'CREATE_PARENT',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => "Created parent record ID #{$parent->id} ('{$parent->name}')",
            ]);
        } catch (\Throwable $e) {
            // Silence log errors
        }

        return redirect()->route('parents.index')
            ->with('success', 'Waalidka cusub waa lagu daray si guul leh!');
    }

    /**
     * Soo bandhig faahfaahinta hal waalid (Show page).
     */
    public function show(SchoolParent $parent)
    {
        return view('parents.show', compact('parent'));
    }

    /**
     * Soo bandhig foomka wax ka badalka waalidka (Edit page).
     */
    public function edit(SchoolParent $parent)
    {
        return view('parents.edit', compact('parent'));
    }

    /**
     * Ku cusboonaysii xogta waalidka database-ka (Update action).
     */
    public function update(UpdateParentRequest $request, SchoolParent $parent)
    {
        $parent->update($request->validated());

        try {
            SecurityLog::create([
                'user_id' => auth()->id(),
                'event_type' => 'UPDATE_PARENT',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => "Updated parent record ID #{$parent->id} ('{$parent->name}')",
            ]);
        } catch (\Throwable $e) {
            // Silence log errors
        }

        return redirect()->route('parents.index')
            ->with('success', 'Xogta waalidka waa la cusboonaysiiyay!');
    }

    /**
     * Ka tiri waalidka database-ka (Destroy action).
     */
    public function destroy(Request $request, SchoolParent $parent)
    {
        $parentId = $parent->id;
        $parentName = $parent->name;

        $parent->delete();

        try {
            SecurityLog::create([
                'user_id' => auth()->id(),
                'event_type' => 'DELETE_PARENT',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => "Deleted parent record ID #{$parentId} ('{$parentName}')",
            ]);
        } catch (\Throwable $e) {
            // Silence log errors
        }

        return redirect()->route('parents.index')
            ->with('success', 'Waalidka waa la tiray si guul leh!');
    }
}

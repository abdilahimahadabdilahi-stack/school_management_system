<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolParent;
use App\Notifications\AnnouncementNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::with(['parent', 'student'])
            ->latest('created_at')
            ->paginate(15);

        return view('announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        return view('announcements.create', [
            'parents' => SchoolParent::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:parents,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $announcement = Announcement::create($validated);
        $parentId = data_get($validated, 'parent_id');
        $recipients = $parentId
            ? SchoolParent::whereKey($parentId)->get()
            : SchoolParent::query()->whereNotNull('email')->get();

        foreach ($recipients as $parent) {
            Notification::route('mail', $parent->email)
                ->notify(new AnnouncementNotification($announcement));
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement): View
    {
        $announcement->load(['parent', 'student']);

        return view('announcements.show', compact('announcement'));
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}

@extends('layouts.app-bs')

@section('title', 'Announcements')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Announcements</h3>
        <a href="{{ route('announcements.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> New Announcement</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-custom">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Title</th><th>Parent</th><th>Student</th><th>Created</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr>
                            <td><a href="{{ route('announcements.show', $announcement) }}" class="fw-semibold text-decoration-none">{{ $announcement->title }}</a><div class="text-muted small">{{ Str::limit($announcement->message, 80) }}</div></td>
                            <td>{{ $announcement->parent?->name ?? 'All parents' }}</td>
                            <td>{{ $announcement->student?->name ?? 'All students' }}</td>
                            <td>{{ $announcement->created_at->format('M d, Y H:i') }}</td>
                            <td class="text-end"><form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fa-solid fa-trash"></i></button></form></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">No announcements yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $announcements->links() }}</div>
    </div>
</div>
@endsection

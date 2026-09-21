@extends('layouts.app-bs')

@section('title', $announcement->title)

@section('content')
<div class="container-fluid p-0" style="max-width: 760px;">
    <a href="{{ route('announcements.index') }}" class="text-decoration-none text-muted small fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> Back to Announcements</a>
    <div class="card card-custom mt-3"><div class="card-body p-4"><h3 class="fw-bold">{{ $announcement->title }}</h3><p class="text-muted small">{{ $announcement->created_at->format('M d, Y H:i') }} · {{ $announcement->parent?->name ?? 'All parents' }}</p><p class="mb-0">{{ $announcement->message }}</p></div></div>
</div>
@endsection

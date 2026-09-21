@extends('layouts.app-bs')

@section('title', 'New Announcement')

@section('content')
<div class="container-fluid p-0" style="max-width: 760px;">
    <div class="card card-custom">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-4">Create Announcement</h3>
            <form action="{{ route('announcements.store') }}" method="POST">
                @csrf
                <div class="mb-3"><label class="form-label">Title</label><input name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="mb-3"><label class="form-label">Parent</label><select name="parent_id" class="form-select"><option value="">All parents</option>@foreach($parents as $parent)<option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }} ({{ $parent->email }})</option>@endforeach</select></div>
                <div class="mb-4"><label class="form-label">Message</label><textarea name="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>@error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <a href="{{ route('announcements.index') }}" class="btn btn-light border me-2">Cancel</a><button class="btn btn-primary">Publish Announcement</button>
            </form>
        </div>
    </div>
</div>
@endsection

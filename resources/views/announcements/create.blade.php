@extends('layouts.app-bs')

@section('title', 'New Announcement')

@section('content')
<div class="container-fluid p-0" style="max-width: 820px;">
    <div class="mb-4">
        <a href="{{ route('announcements.index') }}" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Announcements
        </a>
        <h1 class="h3 fw-bold mt-3 mb-1">Create announcement</h1>
        <p class="text-muted mb-0">Share an update with all parents or one specific family.</p>
    </div>

    <div class="card card-custom">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('announcements.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="title" class="form-label">Title</label>
                    <input id="title" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Parent meeting reminder" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label for="parent_id" class="form-label">Recipient</label>
                    <select id="parent_id" name="parent_id" class="form-select">
                        <option value="">All parents</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }} ({{ $parent->email }})</option>
                        @endforeach
                    </select>
                    <div class="form-text">Leave this as All parents to send the announcement to every parent with an email address.</div>
                </div>
                <div class="mb-4">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" name="message" rows="7" class="form-control @error('message') is-invalid @enderror" placeholder="Write the announcement message..." required>{{ old('message') }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                    <a href="{{ route('announcements.index') }}" class="btn btn-light border order-2 order-sm-1">Cancel</a>
                    <button type="submit" class="btn btn-primary order-1 order-sm-2"><i class="fa-solid fa-paper-plane me-1"></i> Publish announcement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

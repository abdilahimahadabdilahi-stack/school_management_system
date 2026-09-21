@extends('layouts.app-bs')

@section('title', 'Manager Profile')

@section('content')
<div class="container-fluid p-0" style="max-width: 700px;">
    <div class="mb-4">
        <a href="{{ route('managers.index') }}" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Managers List
        </a>
        <h3 class="fw-bold text-slate-800 m-0 mt-2"><i class="fa-solid fa-id-card text-info me-2"></i>Manager Profile Details</h3>
    </div>

    <div class="card card-custom overflow-hidden mb-4">
        <div class="bg-info bg-opacity-10 p-4 border-bottom border-info border-opacity-20 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 shadow-sm" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-slate-900 m-0">{{ $manager->name }}</h4>
                    <span class="badge badge-soft-info mt-1">{{ $manager->department ?? 'General Department' }}</span>
                </div>
            </div>
            <span class="badge badge-soft-secondary">ID #{{ $manager->id }}</span>
        </div>

        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Email Address</label>
                    <div class="fw-semibold text-dark"><i class="fa-regular fa-envelope me-2 text-info"></i>{{ $manager->email }}</div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Phone Contact</label>
                    <div class="fw-semibold text-dark"><i class="fa-solid fa-phone me-2 text-info"></i>{{ $manager->phone ?? 'N/A' }}</div>
                </div>

                <div class="col-12">
                    <hr class="my-2 opacity-10">
                </div>

                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Department</label>
                    <div class="fw-semibold text-dark"><i class="fa-solid fa-building me-2 text-info"></i>{{ $manager->department ?? 'General' }}</div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Registration Date</label>
                    <div class="fw-semibold text-dark"><i class="fa-regular fa-calendar-check me-2 text-info"></i>{{ $manager->created_at ? $manager->created_at->format('M d, Y H:i') : 'N/A' }}</div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="{{ route('managers.index') }}" class="btn btn-light border px-3 fw-semibold">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to List
                </a>
                <a href="{{ route('managers.edit', $manager->id) }}" class="btn btn-info text-white px-4 fw-semibold shadow-sm">
                    <i class="fa-solid fa-pen me-1"></i> Edit Details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
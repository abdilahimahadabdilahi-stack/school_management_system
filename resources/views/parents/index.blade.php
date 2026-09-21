@extends('layouts.app-bs')

@section('title', 'Parents List')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-slate-800 m-0"><i class="fa-solid fa-people-roof text-warning me-2"></i>Parents List</h3>
            <p class="text-muted small m-0 mt-1">Manage parent profiles, contact details, and student relations</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('parents.create') }}" class="btn btn-warning text-white fw-semibold shadow-sm px-3">
                <i class="fa-solid fa-plus me-1"></i> Add New Parent
            </a>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Parent Name</th>
                            <th>Email Address</th>
                            <th>Phone</th>
                            <th>Occupation</th>
                            <th>Address</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parents as $parent)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $parent->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 36px; height: 36px;">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div class="fw-bold text-dark">{{ $parent->name }}</div>
                                    </div>
                                </td>
                                <td class="text-secondary"><i class="fa-regular fa-envelope me-1 text-muted"></i> {{ $parent->email }}</td>
                                <td class="text-secondary"><i class="fa-solid fa-phone me-1 text-muted"></i> {{ $parent->phone ?? 'N/A' }}</td>
                                <td><span class="badge badge-soft-info">{{ $parent->occupation ?? 'N/A' }}</span></td>
                                <td class="text-secondary">{{ $parent->address ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('parents.show', $parent->id) }}" class="btn btn-sm btn-light border text-info" title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-sm btn-light border text-warning" title="Edit Parent">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('parents.destroy', $parent->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma meel marisaa tirida waalidkan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Parent">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-people-roof fs-1 text-slate-300 d-block mb-3"></i>
                                    <h5>No Parents Found</h5>
                                    <p class="small text-muted mb-3">Click the button below to add your first parent record.</p>
                                    <a href="{{ route('parents.create') }}" class="btn btn-sm btn-warning text-white fw-bold">
                                        <i class="fa-solid fa-plus me-1"></i> Add Parent
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($parents->hasPages())
            <div class="card-footer bg-transparent border-0 p-3">
                {{ $parents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

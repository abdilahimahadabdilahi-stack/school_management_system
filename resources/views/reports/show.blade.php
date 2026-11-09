@extends('layouts.app-bs')

@section('title', $report['label'].' Report')

@section('content')
<div class="container-fluid p-0">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-decoration-none text-muted small fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> All Reports</a>
            <h3 class="fw-bold mt-2 mb-0">{{ $report['label'] }} Report</h3>
        </div>
        <form class="d-flex" method="GET">
            <input name="search" value="{{ $search ?? request('search') }}" class="form-control me-2" placeholder="Search by name, email, title, subject, or status">
            <button class="btn btn-outline-primary" type="submit"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>
    <div class="card card-custom">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        @foreach($report['columns'] as $column)
                            <th>{{ str_replace('_', ' ', ucfirst($column)) }}</th>
                        @endforeach
                        @if($reportSlug === 'users')
                            <th class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            @foreach($report['columns'] as $column)
                                <td>{{ is_object($record->{$column}) ? $record->{$column}->format('M d, Y H:i') : ($record->{$column} ?? 'N/A') }}</td>
                            @endforeach
                            @if($reportSlug === 'users')
                                <td class="text-end">
                                    <form action="{{ route('reports.users.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($report['columns']) + ($reportSlug === 'users' ? 1 : 0) }}" class="text-center text-muted py-5">
                                No records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $records->links() }}</div>
    </div>
</div>
@endsection

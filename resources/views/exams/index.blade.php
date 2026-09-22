<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exams - School SMS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-file-pen text-danger me-2"></i>Exams List</h2>
            <p class="text-muted m-0">Maamul oo eeg dhammaan imtixaanaadka dugsiga</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
            <a href="{{ route('exams.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Add New Exam</a>
        </div>
    </div>

    <form method="GET" action="{{ route('exams.index') }}" class="row g-2 mb-4">
        <div class="col-md-10">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search exams by name, subject, or date">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
            @if(request('search'))
                <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            @endif
        </div>
    </form>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Exam Name</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>Total Marks</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $loop->iteration }}</td>
                                <td><span class="fw-semibold text-primary">{{ $exam->name }}</span></td>
                                <td>{{ $exam->subject }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $exam->exam_date }}</span></td>
                                <td>{{ $exam->start_time ?? 'N/A' }}</td>
                                <td><span class="badge bg-info text-dark">{{ $exam->total_marks }} Marks</span></td>
                                <td class="text-end pe-3">
                                    <!-- View / Show Button -->
                                    <a href="{{ route('exams.show', $exam->id) }}" class="btn btn-sm btn-outline-info me-1">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                    <!-- Edit Button -->
                                    <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <!-- Delete Form -->
                                    <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma xaqiijinaysaa inaad tirtirto imtixaankan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-folder-open fs-3 d-block mb-2"></i>
                                    Wax imtixaan ah weli ma jiraan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($exams->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $exams->links() }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
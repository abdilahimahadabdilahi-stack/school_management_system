<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-clipboard-check me-2 text-primary"></i>Attendance Records</h2>
        <div>
            <a href="{{ route('attendance.create', ['date' => $date]) }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Take / Mark Attendance</a>
            <a href="/" class="btn btn-secondary ms-1"><i class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Date Filter -->
    <form action="{{ route('attendance.index') }}" method="GET" class="row g-3 mb-4">
        <div class="col-auto d-flex align-items-center">
            <label class="fw-bold me-2">Taariikhda:</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
        </div>
    </form>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#ID</th>
                <th>Ardayga</th>
                <th>Taariikhda</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td class="fw-bold">{{ $item->student->name ?? 'N/A' }}</td>
                    <td>{{ $item->attendance_date }}</td>
                    <td>
                        @if($item->status == 'present')
                            <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Present</span>
                        @elseif($item->status == 'late')
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Late</span>
                        @else
                            <span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i> Absent</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('attendance.show', $item->student_id) }}" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-eye"></i> View Student Report</a>
                        <a href="{{ route('attendance.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i> Edit</a>
                        
                        <form action="{{ route('attendance.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma meelaynaysaa in aad tirtirto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Lama helin wax xaadirin ah taariikhdan ({{ $date }}). Ku dhufo **Mark Attendance** si aad u xaadiriso.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
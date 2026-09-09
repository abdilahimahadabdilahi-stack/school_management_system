<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-clipboard-user me-2 text-primary"></i>Mark Attendance</h2>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <form action="{{ route('attendance.create') }}" method="GET" class="row g-3 mb-4">
        <div class="col-auto d-flex align-items-center">
            <label class="fw-bold me-2">Taariikhda Xaadirinta:</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
        </div>
    </form>

    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="attendance_date" value="{{ $date }}">

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#ID</th>
                    <th>Student Name</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    @php
                        $status = $existingAttendances[$student->id] ?? 'present';
                    @endphp
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td class="fw-semibold">{{ $student->name }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="attendances[{{ $student->id }}]" id="p_{{ $student->id }}" value="present" {{ $status == 'present' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="p_{{ $student->id }}"><i class="fa-solid fa-check me-1"></i> Present</label>

                                <input type="radio" class="btn-check" name="attendances[{{ $student->id }}]" id="l_{{ $student->id }}" value="late" {{ $status == 'late' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning" for="l_{{ $student->id }}"><i class="fa-solid fa-clock me-1"></i> Late</label>

                                <input type="radio" class="btn-check" name="attendances[{{ $student->id }}]" id="a_{{ $student->id }}" value="absent" {{ $status == 'absent' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="a_{{ $student->id }}"><i class="fa-solid fa-xmark me-1"></i> Absent</label>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Lama helin wax arday ah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($students->count() > 0)
            <button type="submit" class="btn btn-success btn-lg w-100 mt-3"><i class="fa-solid fa-floppy-disk me-2"></i> Save Attendance</button>
        @endif
    </form>
</div>

</body>
</html>
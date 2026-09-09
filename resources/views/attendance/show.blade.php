<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-user-check me-2 text-info"></i>Attendance Summary: {{ $student->name }}</h2>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="p-3 bg-success text-white rounded text-center">
                <i class="fa-solid fa-check-circle fs-3 mb-1"></i>
                <h5>Present Days</h5>
                <h2>{{ $stats['present'] }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-warning text-dark rounded text-center">
                <i class="fa-solid fa-clock fs-3 mb-1"></i>
                <h5>Late Days</h5>
                <h2>{{ $stats['late'] }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-danger text-white rounded text-center">
                <i class="fa-solid fa-circle-xmark fs-3 mb-1"></i>
                <h5>Absent Days</h5>
                <h2>{{ $stats['absent'] }}</h2>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <h5 class="fw-bold mb-3">Full Attendance History</h5>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($student->attendances as $record)
                <tr>
                    <td>{{ $record->attendance_date }}</td>
                    <td>
                        @if($record->status == 'present')
                            <span class="badge bg-success">Present</span>
                        @elseif($record->status == 'late')
                            <span class="badge bg-warning text-dark">Late</span>
                        @else
                            <span class="badge bg-danger">Absent</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">Wax xaadirin ah looma hayo ardaygan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Staff Member Details</h2>
        <a href="{{ route('staff.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="card p-3">
        <div class="mb-3">
            <strong>#ID:</strong> {{ $staff->id }}
        </div>
        <div class="mb-3">
            <strong>Full Name:</strong> {{ $staff->name }}
        </div>
        <div class="mb-3">
            <strong>Email Address:</strong> {{ $staff->email }}
        </div>
        <div class="mb-3">
            <strong>Phone Number:</strong> {{ $staff->phone }}
        </div>
        <div class="mb-3">
            <strong>Role / Position:</strong> {{ $staff->role }}
        </div>
        <div class="mb-3">
            <strong>Created At:</strong> {{ $staff->created_at->format('Y-m-d H:i') }}
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-warning text-white">Edit Staff</a>
        
        <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Ma weydiinaysaa inaad tirtirto shaqaalahan?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Staff</button>
        </form>
    </div>
</div>

</body>
</html>
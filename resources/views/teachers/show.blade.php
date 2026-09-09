<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Teacher Details</h2>
        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="card p-3">
        <div class="mb-3">
            <strong>#ID:</strong> {{ $teacher->id }}
        </div>
        <div class="mb-3">
            <strong>Full Name:</strong> {{ $teacher->name }}
        </div>
        <div class="mb-3">
            <strong>Email Address:</strong> {{ $teacher->email }}
        </div>
        <div class="mb-3">
            <strong>Phone Number:</strong> {{ $teacher->phone }}
        </div>
        <div class="mb-3">
            <strong>Subject:</strong> {{ $teacher->subject }}
        </div>
        <div class="mb-3">
            <strong>Created At:</strong> {{ $teacher->created_at->format('Y-m-d H:i') }}
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-warning text-white">Edit Teacher</a>
        
        <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Ma weydiinaysaa inaad tirtirto macallinkan?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Teacher</button>
        </form>
    </div>
</div>

</body>
</html>
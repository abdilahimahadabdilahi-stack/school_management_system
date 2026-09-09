<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Student Details</h4>
            <a href="{{ route('students.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item"><strong>ID:</strong> {{ $student->id }}</li>
                <li class="list-group-item"><strong>Name:</strong> {{ $student->name }}</li>
                <li class="list-group-item"><strong>Age:</strong> {{ $student->age }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $student->email }}</li>
                <li class="list-group-item"><strong>Class:</strong> {{ $student->class }}</li>
            </ul>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning text-white">Edit Student</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management - Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>List of Students</h2>
        <a href="{{ route('students.create') }}" class="btn btn-primary">+ Add New Student</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Email</th>
                <th>Class</th>
                <th>Subject</th> <!-- Tiirka Cusub -->
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->age }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->class_name }}</td>
                    <td>{{ $student->subject ?? 'N/A' }}</td> <!-- Muujinta Maaddada -->
                    <td class="text-center">
                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm text-white" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm text-white" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma weydiinaysaa inaad tirtirto ardaygan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Lama helin wax arday ah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
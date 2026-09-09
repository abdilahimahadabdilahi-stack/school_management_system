<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management - Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>List of Staff</h2>
        <a href="{{ route('staff.create') }}" class="btn btn-primary">+ Add New Staff</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staffs as $staff)
                <tr>
                    <td>{{ $staff->id }}</td>
                    <td>{{ $staff->name }}</td>
                    <td>{{ $staff->email }}</td>
                    <td>{{ $staff->phone }}</td>
                    <td>{{ $staff->role }}</td>
                    <td class="text-center">
                        <a href="{{ route('staff.show', $staff->id) }}" class="btn btn-info btn-sm text-white" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-warning btn-sm text-white" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma weydiinaysaa inaad tirtirto shaqaalahan?');">
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
                    <td colspan="6" class="text-center">Lama helin wax shaqaale ah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
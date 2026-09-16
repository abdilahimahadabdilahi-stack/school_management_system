<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Managers - SchoolSMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-user-tie text-primary me-2"></i>Managers List</h2>
        <a href="{{ route('managers.create') }}" class="btn btn-primary fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Add New Manager
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover table-striped align-middle m-0">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($managers as $manager)
                        <tr>
                            <td>{{ $manager->id }}</td>
                            <td class="fw-bold">{{ $manager->name }}</td>
                            <td>{{ $manager->email }}</td>
                            <td>{{ $manager->phone ?? 'N/A' }}</td>
                            <td><span class="badge bg-info text-dark">{{ $manager->department ?? 'General' }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('managers.show', $manager->id) }}" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('managers.edit', $manager->id) }}" class="btn btn-sm btn-warning text-white"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('managers.destroy', $manager->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma meel marisaa tirida manager-kan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Wax manager ah oo la helay ma jiraan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $managers->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
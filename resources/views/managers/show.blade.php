<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Manager - SchoolSMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white fw-bold">
            <i class="fa-solid fa-user-check me-2"></i> Manager Profile Details
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item"><strong>ID:</strong> {{ $manager->id }}</li>
                <li class="list-group-item"><strong>Name:</strong> {{ $manager->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $manager->email }}</li>
                <li class="list-group-item"><strong>Phone:</strong> {{ $manager->phone ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Department:</strong> {{ $manager->department ?? 'General' }}</li>
                <li class="list-group-item"><strong>Created At:</strong> {{ $manager->created_at->format('Y-m-d H:i') }}</li>
            </ul>

            <div class="d-flex justify-content-between">
                <a href="{{ route('managers.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
                <a href="{{ route('managers.edit', $manager->id) }}" class="btn btn-warning text-white"><i class="fa-solid fa-pen me-1"></i> Edit Details</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - SchoolSMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-chart-line text-primary me-2"></i>System Reports</h2>
        <div>
            <a href="{{ route('reports.create') }}" class="btn btn-primary me-2">
                <i class="fa-solid fa-plus me-1"></i> Create New Report
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="card shadow-sm p-4">
        <h4>System Summary Report</h4>
        <p class="text-muted">Halkan waa bogga warbixinnada guud ee dugsiga.</p>
        
        <table class="table table-bordered align-middle mt-3">
            <thead class="table-dark">
                <tr>
                    <th>#ID</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Student Reports</td>
                    <td><span class="badge bg-success">Ready</span></td>
                    <td class="text-center">
                        <a href="{{ route('reports.show', 1) }}" class="btn btn-sm btn-info text-white me-1">
                            <i class="fa-solid fa-eye"></i> Show
                        </a>
                        <a href="{{ route('reports.edit', 1) }}" class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Teacher Reports</td>
                    <td><span class="badge bg-success">Ready</span></td>
                    <td class="text-center">
                        <a href="{{ route('reports.show', 2) }}" class="btn btn-sm btn-info text-white me-1">
                            <i class="fa-solid fa-eye"></i> Show
                        </a>
                        <a href="{{ route('reports.edit', 2) }}" class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Attendance Reports</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td class="text-center">
                        <a href="{{ route('reports.show', 3) }}" class="btn btn-sm btn-info text-white me-1">
                            <i class="fa-solid fa-eye"></i> Show
                        </a>
                        <a href="{{ route('reports.edit', 3) }}" class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
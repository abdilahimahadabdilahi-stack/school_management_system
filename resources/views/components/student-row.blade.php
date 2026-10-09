@props(['student', 'showActions' => false])

<tr>
    <td class="fw-bold text-muted">#{{ $student->id }}</td>
    <td class="fw-semibold text-dark">{{ $student->name }}</td>
    <td>{{ $student->age }}</td>
    <td class="text-secondary">{{ $student->email }}</td>
    <td>{{ $student->class_name }}</td>
    <td>{{ $student->section ?? 'N/A' }}</td>
    <td>{{ $student->parent?->name ?? 'N/A' }}</td>
    <td>{{ $student->subject ?? 'N/A' }}</td>
    @if ($showActions)
        <td class="text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-light border text-info" title="View Student">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-light border text-warning" title="Edit Student">
                    <i class="fa-solid fa-pen"></i>
                </a>
                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma weydiinaysaa inaad tirtirto ardaygan?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Student">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    @endif
</tr>

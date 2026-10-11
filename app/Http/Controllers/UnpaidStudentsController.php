<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\View\View;

class UnpaidStudentsController extends Controller
{
    public function index(): View
    {
        $students = Student::query()
            ->with(['parent', 'payments' => fn ($query) => $query->orderByDesc('payment_date')->orderByDesc('id')])
            ->get()
            ->map(function (Student $student): ?array {
                $latestPayment = $student->payments->first();
                $status = strtolower((string) ($latestPayment->status ?? 'unpaid'));
                $balance = (float) ($latestPayment->balance ?? 0);

                if ($latestPayment === null) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'class_name' => $student->class_name ?? 'N/A',
                        'fee_amount_due' => '0.00',
                        'status' => 'Unpaid',
                        'phone' => $student->parent?->phone ?? 'N/A',
                    ];
                }

                if ($status === 'paid' && $balance <= 0) {
                    return null;
                }

                if ($status !== 'unpaid' && $status !== 'partial' && $balance <= 0) {
                    return null;
                }

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'class_name' => $student->class_name ?? 'N/A',
                    'fee_amount_due' => number_format(max($balance, 0), 2),
                    'status' => match ($status) {
                        'partial' => 'Partial',
                        'unpaid' => 'Unpaid',
                        default => ucfirst($status),
                    },
                    'phone' => $student->parent?->phone ?? 'N/A',
                ];
            })
            ->filter()
            ->values();

        return view('students.unpaid', compact('students'));
    }
}

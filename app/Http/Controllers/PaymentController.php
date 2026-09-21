<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // 1. Tusi dhammaan lacagaha
    public function index()
    {
        $payments = Payment::with(['student', 'schoolClass'])->latest()->get();

        return view('payments.index', compact('payments'));
    }

    // 2. Tusi form-ka lacagta cusub
    public function create()
    {
        $students = Student::orderBy('name')->get();
        $classes = SchoolClass::orderBy('class_number')->orderBy('section')->get();

        return view('payments.create', compact('students', 'classes'));
    }

    // 3. Keydi lacagta cusub
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'amount' => 'required|numeric|min:0',
            'total_fee' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money',
            'payment_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        $balance = $request->total_fee - $request->amount;
        $status = 'unpaid';
        if ($request->amount >= $request->total_fee) {
            $status = 'paid';
            $balance = 0;
        } elseif ($request->amount > 0) {
            $status = 'partial';
        }

        // Generate receipt number
        $receiptNumber = 'RCP-'.date('Ymd').'-'.str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

        Payment::create([
            'student_id' => $request->student_id,
            'school_class_id' => $request->school_class_id,
            'amount' => $request->amount,
            'total_fee' => $request->total_fee,
            'balance' => $balance,
            'status' => $status,
            'payment_method' => $request->payment_method,
            'receipt_number' => $receiptNumber,
            'payment_date' => $request->payment_date,
            'description' => $request->description,
        ]);

        return redirect()->route('payments.index')->with('success', 'Lacagta waa la keydiyay! Receipt: '.$receiptNumber);
    }

    // 4. Tusi faahfaahinta lacagta
    public function show($id)
    {
        $payment = Payment::with(['student', 'schoolClass'])->findOrFail($id);

        return view('payments.show', compact('payment'));
    }

    // 5. Tusi form-ka xogta lagu beddelo
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $students = Student::orderBy('name')->get();
        $classes = SchoolClass::orderBy('class_number')->orderBy('section')->get();

        return view('payments.edit', compact('payment', 'students', 'classes'));
    }

    // 6. Cusbooneysii xogta lacagta
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'amount' => 'required|numeric|min:0',
            'total_fee' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money',
            'payment_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        $balance = $request->total_fee - $request->amount;
        $status = 'unpaid';
        if ($request->amount >= $request->total_fee) {
            $status = 'paid';
            $balance = 0;
        } elseif ($request->amount > 0) {
            $status = 'partial';
        }

        $payment->update([
            'student_id' => $request->student_id,
            'school_class_id' => $request->school_class_id,
            'amount' => $request->amount,
            'total_fee' => $request->total_fee,
            'balance' => $balance,
            'status' => $status,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'description' => $request->description,
        ]);

        return redirect()->route('payments.index')->with('success', 'Xogta lacagta waa la cusbooneysiiyay!');
    }

    // 7. Tirtir lacagta
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Lacagta waa la tirtiray!');
    }
}

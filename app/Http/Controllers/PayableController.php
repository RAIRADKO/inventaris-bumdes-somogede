<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use App\Models\PayablePayment;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayableController extends Controller
{
    public function index(Request $request)
    {
        $query = Payable::with(['supplier', 'businessUnit'])
            ->latest('date');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $payables = $query->paginate(20);
        $suppliers = Supplier::active()->get();

        $totalUnpaid = Payable::unpaid()->sum('remaining_amount');

        return view('payable.index', compact('payables', 'suppliers', 'totalUnpaid'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->get();
        return view('payable.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['invoice_number'] = Payable::generateNumber();
        $validated['remaining_amount'] = $validated['amount'];
        $validated['paid_amount'] = 0;
        $validated['status'] = 'unpaid';
        $validated['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/payable', 'public');
        }

        $payable = Payable::create($validated);

        return redirect()
            ->route('payable.show', $payable)
            ->with('success', 'Hutang berhasil dibuat.');
    }

    public function show(Payable $payable)
    {
        $payable->load(['supplier', 'businessUnit', 'payments.createdBy', 'createdBy']);
        return view('payable.show', compact('payable'));
    }

    public function edit(Payable $payable)
    {
        if ($payable->paid_amount > 0) {
            return back()->with('error', 'Hutang yang sudah ada pembayaran tidak dapat diedit.');
        }

        $suppliers = Supplier::active()->get();
        return view('payable.edit', compact('payable', 'suppliers'));
    }

    public function update(Request $request, Payable $payable)
    {
        if ($payable->paid_amount > 0) {
            return back()->with('error', 'Hutang yang sudah ada pembayaran tidak dapat diedit.');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['remaining_amount'] = $validated['amount'];

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/payable', 'public');
        }

        $payable->update($validated);

        return redirect()
            ->route('payable.show', $payable)
            ->with('success', 'Hutang berhasil diperbarui.');
    }

    public function destroy(Payable $payable)
    {
        if ($payable->paid_amount > 0) {
            return back()->with('error', 'Hutang yang sudah ada pembayaran tidak dapat dihapus.');
        }

        $payable->delete();

        return redirect()
            ->route('payable.index')
            ->with('success', 'Hutang berhasil dihapus.');
    }

    public function addPayment(Request $request, Payable $payable)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0|max:' . $payable->remaining_amount,
            'payment_method' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['payment_number'] = PayablePayment::generateNumber();
        $validated['payable_id'] = $payable->id;
        $validated['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/payable-payment', 'public');
        }

        PayablePayment::create($validated);

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }
}

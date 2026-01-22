<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Receivable;
use App\Models\ReceivablePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceivableController extends Controller
{
    public function index(Request $request)
    {
        $query = Receivable::with(['customer', 'businessUnit'])
            ->latest('date');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
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

        $receivables = $query->paginate(20);
        $customers = Customer::active()->get();

        // Summary
        $totalUnpaid = Receivable::unpaid()->sum('remaining_amount');
        $totalOverdue = Receivable::overdue()->sum('remaining_amount');

        return view('receivable.index', compact('receivables', 'customers', 'totalUnpaid', 'totalOverdue'));
    }

    public function create()
    {
        $customers = Customer::active()->get();
        return view('receivable.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['invoice_number'] = Receivable::generateNumber();
        $validated['remaining_amount'] = $validated['amount'];
        $validated['paid_amount'] = 0;
        $validated['status'] = 'unpaid';
        $validated['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/receivable', 'public');
        }

        $receivable = Receivable::create($validated);

        return redirect()
            ->route('receivable.show', $receivable)
            ->with('success', 'Piutang berhasil dibuat.');
    }

    public function show(Receivable $receivable)
    {
        $receivable->load(['customer', 'businessUnit', 'payments.createdBy', 'createdBy']);
        return view('receivable.show', compact('receivable'));
    }

    public function edit(Receivable $receivable)
    {
        if ($receivable->paid_amount > 0) {
            return back()->with('error', 'Piutang yang sudah ada pembayaran tidak dapat diedit.');
        }

        $customers = Customer::active()->get();
        return view('receivable.edit', compact('receivable', 'customers'));
    }

    public function update(Request $request, Receivable $receivable)
    {
        if ($receivable->paid_amount > 0) {
            return back()->with('error', 'Piutang yang sudah ada pembayaran tidak dapat diedit.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['remaining_amount'] = $validated['amount'];

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/receivable', 'public');
        }

        $receivable->update($validated);

        return redirect()
            ->route('receivable.show', $receivable)
            ->with('success', 'Piutang berhasil diperbarui.');
    }

    public function destroy(Receivable $receivable)
    {
        if ($receivable->paid_amount > 0) {
            return back()->with('error', 'Piutang yang sudah ada pembayaran tidak dapat dihapus.');
        }

        $receivable->delete();

        return redirect()
            ->route('receivable.index')
            ->with('success', 'Piutang berhasil dihapus.');
    }

    public function addPayment(Request $request, Receivable $receivable)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0|max:' . $receivable->remaining_amount,
            'payment_method' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['payment_number'] = ReceivablePayment::generateNumber();
        $validated['receivable_id'] = $receivable->id;
        $validated['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/receivable-payment', 'public');
        }

        ReceivablePayment::create($validated);

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function agingReport()
    {
        $aging = [
            'current' => Receivable::unpaid()->where('due_date', '>=', now())->get(),
            '1-30' => Receivable::unpaid()
                ->where('due_date', '<', now())
                ->where('due_date', '>=', now()->subDays(30))
                ->get(),
            '31-60' => Receivable::unpaid()
                ->where('due_date', '<', now()->subDays(30))
                ->where('due_date', '>=', now()->subDays(60))
                ->get(),
            '61-90' => Receivable::unpaid()
                ->where('due_date', '<', now()->subDays(60))
                ->where('due_date', '>=', now()->subDays(90))
                ->get(),
            '90+' => Receivable::unpaid()
                ->where('due_date', '<', now()->subDays(90))
                ->get(),
        ];

        return view('receivable.aging', compact('aging'));
    }
}

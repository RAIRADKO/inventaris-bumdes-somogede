<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\ExpenseTransaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = ExpenseTransaction::with(['category', 'businessUnit', 'createdBy'])
            ->latest('date');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('business_unit_id')) {
            $query->where('business_unit_id', $request->business_unit_id);
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

        $transactions = $query->paginate(20);
        $categories = TransactionCategory::expense()->active()->get();
        $businessUnits = BusinessUnit::where('is_active', true)->get();

        $totalApproved = ExpenseTransaction::approved()->sum('amount');

        return view('expense.index', compact('transactions', 'categories', 'businessUnits', 'totalApproved'));
    }

    public function create()
    {
        $categories = TransactionCategory::expense()->active()->get();
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        return view('expense.create', compact('categories', 'businessUnits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:transaction_categories,id',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'recipient' => 'nullable|string|max:200',
            'reference' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['transaction_number'] = ExpenseTransaction::generateNumber();
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'draft';

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/expense', 'public');
        }

        $transaction = ExpenseTransaction::create($validated);

        return redirect()
            ->route('expense.show', $transaction)
            ->with('success', 'Transaksi pengeluaran berhasil dibuat.');
    }

    public function show(ExpenseTransaction $expense)
    {
        $expense->load(['category', 'businessUnit', 'createdBy', 'approvedBy', 'journal.entries.account']);
        return view('expense.show', compact('expense'));
    }

    public function edit(ExpenseTransaction $expense)
    {
        if ($expense->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat diedit.');
        }

        $categories = TransactionCategory::expense()->active()->get();
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        return view('expense.edit', compact('expense', 'categories', 'businessUnits'));
    }

    public function update(Request $request, ExpenseTransaction $expense)
    {
        if ($expense->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat diedit.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:transaction_categories,id',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'recipient' => 'nullable|string|max:200',
            'reference' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/expense', 'public');
        }

        $expense->update($validated);

        return redirect()
            ->route('expense.show', $expense)
            ->with('success', 'Transaksi pengeluaran berhasil diperbarui.');
    }

    public function destroy(ExpenseTransaction $expense)
    {
        if ($expense->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat dihapus.');
        }

        $expense->delete();

        return redirect()
            ->route('expense.index')
            ->with('success', 'Transaksi pengeluaran berhasil dihapus.');
    }

    public function submit(ExpenseTransaction $expense)
    {
        if ($expense->status !== 'draft') {
            return back()->with('error', 'Transaksi sudah disubmit.');
        }

        $expense->update(['status' => 'pending']);

        return back()->with('success', 'Transaksi berhasil disubmit untuk persetujuan.');
    }

    public function approve(ExpenseTransaction $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak dalam status pending.');
        }

        if (!Auth::user()->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui transaksi.');
        }

        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Transaksi berhasil disetujui.');
    }

    public function reject(ExpenseTransaction $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak dalam status pending.');
        }

        if (!Auth::user()->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menolak transaksi.');
        }

        $expense->update(['status' => 'rejected']);

        return back()->with('success', 'Transaksi berhasil ditolak.');
    }
}

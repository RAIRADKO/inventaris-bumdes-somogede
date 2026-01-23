<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\IncomeTransaction;
use App\Models\TransactionCategory;
use App\Services\JournalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = IncomeTransaction::with(['category', 'businessUnit', 'createdBy'])
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
        $categories = TransactionCategory::income()->active()->get();
        $businessUnits = BusinessUnit::where('is_active', true)->get();

        $totalApproved = IncomeTransaction::approved()->sum('amount');

        return view('income.index', compact('transactions', 'categories', 'businessUnits', 'totalApproved'));
    }

    public function create()
    {
        $categories = TransactionCategory::income()->active()->get();
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        return view('income.create', compact('categories', 'businessUnits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:transaction_categories,id',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'source' => 'nullable|string|max:200',
            'reference' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['transaction_number'] = IncomeTransaction::generateNumber();
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'draft';

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/income', 'public');
        }

        $transaction = IncomeTransaction::create($validated);

        return redirect()
            ->route('income.show', $transaction)
            ->with('success', 'Transaksi pemasukan berhasil dibuat.');
    }

    public function show(IncomeTransaction $income)
    {
        $income->load(['category', 'businessUnit', 'createdBy', 'approvedBy', 'journal.entries.account']);
        return view('income.show', compact('income'));
    }

    public function edit(IncomeTransaction $income)
    {
        if ($income->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat diedit.');
        }

        $categories = TransactionCategory::income()->active()->get();
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        return view('income.edit', compact('income', 'categories', 'businessUnits'));
    }

    public function update(Request $request, IncomeTransaction $income)
    {
        if ($income->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat diedit.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:transaction_categories,id',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'source' => 'nullable|string|max:200',
            'reference' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/income', 'public');
        }

        $income->update($validated);

        return redirect()
            ->route('income.show', $income)
            ->with('success', 'Transaksi pemasukan berhasil diperbarui.');
    }

    public function destroy(IncomeTransaction $income)
    {
        if ($income->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat dihapus.');
        }

        $income->delete();

        return redirect()
            ->route('income.index')
            ->with('success', 'Transaksi pemasukan berhasil dihapus.');
    }

    public function submit(IncomeTransaction $income)
    {
        if ($income->status !== 'draft') {
            return back()->with('error', 'Transaksi sudah disubmit.');
        }

        $income->update(['status' => 'pending']);

        return back()->with('success', 'Transaksi berhasil disubmit untuk persetujuan.');
    }

    public function approve(IncomeTransaction $income)
    {
        if ($income->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak dalam status pending.');
        }

        if (!Auth::user()->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui transaksi.');
        }

        $income->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Create journal entry
        $journalService = new JournalService();
        $journal = $journalService->createFromIncome($income);

        if ($journal) {
            return back()->with('success', 'Transaksi berhasil disetujui dan jurnal telah dibuat.');
        }

        return back()->with('warning', 'Transaksi berhasil disetujui, tetapi jurnal tidak dapat dibuat. Pastikan kategori memiliki akun yang terkait.');
    }

    public function reject(IncomeTransaction $income)
    {
        if ($income->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak dalam status pending.');
        }

        if (!Auth::user()->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menolak transaksi.');
        }

        $income->update(['status' => 'rejected']);

        return back()->with('success', 'Transaksi berhasil ditolak.');
    }
}

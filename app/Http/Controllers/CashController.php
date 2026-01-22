<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{
    public function index(Request $request)
    {
        $query = CashTransaction::with(['category', 'businessUnit', 'createdBy'])
            ->latest('date');

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
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

        // Summary
        $totalIn = CashTransaction::approved()->cashIn()->sum('amount');
        $totalOut = CashTransaction::approved()->cashOut()->sum('amount');
        $balance = $totalIn - $totalOut;

        return view('cash.index', compact('transactions', 'totalIn', 'totalOut', 'balance'));
    }

    public function create()
    {
        $categories = TransactionCategory::active()->get();
        return view('cash.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'reference' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['transaction_number'] = CashTransaction::generateNumber($request->type);
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'draft';

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/cash', 'public');
        }

        $transaction = CashTransaction::create($validated);

        return redirect()
            ->route('cash.show', $transaction)
            ->with('success', 'Transaksi kas berhasil dibuat.');
    }

    public function show(CashTransaction $cash)
    {
        $cash->load(['category', 'businessUnit', 'createdBy', 'approvedBy', 'journal.entries.account']);
        return view('cash.show', compact('cash'));
    }

    public function edit(CashTransaction $cash)
    {
        if ($cash->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat diedit.');
        }

        $categories = TransactionCategory::active()->get();
        return view('cash.edit', compact('cash', 'categories'));
    }

    public function update(Request $request, CashTransaction $cash)
    {
        if ($cash->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat diedit.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'reference' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments/cash', 'public');
        }

        $cash->update($validated);

        return redirect()
            ->route('cash.show', $cash)
            ->with('success', 'Transaksi kas berhasil diperbarui.');
    }

    public function destroy(CashTransaction $cash)
    {
        if ($cash->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat dihapus.');
        }

        $cash->delete();

        return redirect()
            ->route('cash.index')
            ->with('success', 'Transaksi kas berhasil dihapus.');
    }

    public function submit(CashTransaction $cash)
    {
        if ($cash->status !== 'draft') {
            return back()->with('error', 'Transaksi sudah disubmit.');
        }

        $cash->update(['status' => 'pending']);

        return back()->with('success', 'Transaksi berhasil disubmit untuk persetujuan.');
    }

    public function approve(CashTransaction $cash)
    {
        if ($cash->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak dalam status pending.');
        }

        if (!Auth::user()->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui transaksi.');
        }

        $cash->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // TODO: Create journal entry

        return back()->with('success', 'Transaksi berhasil disetujui.');
    }

    public function reject(Request $request, CashTransaction $cash)
    {
        if ($cash->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak dalam status pending.');
        }

        if (!Auth::user()->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menolak transaksi.');
        }

        $cash->update(['status' => 'rejected']);

        return back()->with('success', 'Transaksi berhasil ditolak.');
    }

    public function dailyReport(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $transactions = CashTransaction::with(['category'])
            ->approved()
            ->whereDate('date', $date)
            ->orderBy('created_at')
            ->get();

        $openingBalance = CashTransaction::approved()
            ->where('date', '<', $date)
            ->selectRaw('SUM(CASE WHEN type = "in" THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;

        $totalIn = $transactions->where('type', 'in')->sum('amount');
        $totalOut = $transactions->where('type', 'out')->sum('amount');
        $closingBalance = $openingBalance + $totalIn - $totalOut;

        return view('cash.daily-report', compact(
            'date', 'transactions', 'openingBalance', 'totalIn', 'totalOut', 'closingBalance'
        ));
    }
}

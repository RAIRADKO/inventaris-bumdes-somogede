<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\FiscalPeriod;
use App\Models\Journal;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = Journal::with(['businessUnit', 'createdBy', 'entries'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by business unit
        if ($request->filled('business_unit_id')) {
            $query->where('business_unit_id', $request->business_unit_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('journal_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $journals = $query->paginate(15)->withQueryString();
        $businessUnits = BusinessUnit::active()->get();

        return view('journal.index', compact('journals', 'businessUnits'));
    }

    public function create()
    {
        $businessUnits = BusinessUnit::active()->get();
        $accounts = ChartOfAccount::postable()->orderBy('code')->get();
        $fiscalPeriod = FiscalPeriod::current();

        return view('journal.create', compact('businessUnits', 'accounts', 'fiscalPeriod'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:500',
            'business_unit_id' => 'required|exists:business_units,id',
            'entries' => 'required|array|min:2',
            'entries.*.account_id' => 'required|exists:chart_of_accounts,id',
            'entries.*.debit' => 'nullable|numeric|min:0',
            'entries.*.credit' => 'nullable|numeric|min:0',
            'entries.*.description' => 'nullable|string|max:255',
        ]);

        // Check balance
        $totalDebit = collect($request->entries)->sum('debit');
        $totalCredit = collect($request->entries)->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()
                ->with('error', 'Jurnal tidak balance. Total Debit: ' . number_format($totalDebit, 2) . ', Total Kredit: ' . number_format($totalCredit, 2));
        }

        DB::transaction(function () use ($request, $validated) {
            $fiscalPeriod = FiscalPeriod::current();

            $journal = Journal::create([
                'journal_number' => Journal::generateNumber(),
                'date' => $validated['date'],
                'description' => $validated['description'],
                'business_unit_id' => $validated['business_unit_id'],
                'fiscal_period_id' => $fiscalPeriod?->id,
                'status' => 'draft',
                'type' => 'general',
                'created_by' => auth()->id(),
            ]);

            foreach ($request->entries as $entry) {
                if (($entry['debit'] ?? 0) > 0 || ($entry['credit'] ?? 0) > 0) {
                    JournalEntry::create([
                        'journal_id' => $journal->id,
                        'account_id' => $entry['account_id'],
                        'debit' => $entry['debit'] ?? 0,
                        'credit' => $entry['credit'] ?? 0,
                        'description' => $entry['description'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('journal.index')
            ->with('success', 'Jurnal berhasil dibuat.');
    }

    public function show(Journal $journal)
    {
        $journal->load(['businessUnit', 'fiscalPeriod', 'createdBy', 'approvedBy', 'entries.account']);
        return view('journal.show', compact('journal'));
    }

    public function edit(Journal $journal)
    {
        if ($journal->status !== 'draft') {
            return back()->with('error', 'Hanya jurnal dengan status draft yang dapat diedit.');
        }

        $journal->load('entries');
        $businessUnits = BusinessUnit::active()->get();
        $accounts = ChartOfAccount::postable()->orderBy('code')->get();

        return view('journal.edit', compact('journal', 'businessUnits', 'accounts'));
    }

    public function update(Request $request, Journal $journal)
    {
        if ($journal->status !== 'draft') {
            return back()->with('error', 'Hanya jurnal dengan status draft yang dapat diedit.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:500',
            'business_unit_id' => 'required|exists:business_units,id',
            'entries' => 'required|array|min:2',
            'entries.*.account_id' => 'required|exists:chart_of_accounts,id',
            'entries.*.debit' => 'nullable|numeric|min:0',
            'entries.*.credit' => 'nullable|numeric|min:0',
            'entries.*.description' => 'nullable|string|max:255',
        ]);

        // Check balance
        $totalDebit = collect($request->entries)->sum('debit');
        $totalCredit = collect($request->entries)->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()
                ->with('error', 'Jurnal tidak balance. Total Debit: ' . number_format($totalDebit, 2) . ', Total Kredit: ' . number_format($totalCredit, 2));
        }

        DB::transaction(function () use ($request, $journal, $validated) {
            $journal->update([
                'date' => $validated['date'],
                'description' => $validated['description'],
                'business_unit_id' => $validated['business_unit_id'],
            ]);

            // Delete existing entries and recreate
            $journal->entries()->delete();

            foreach ($request->entries as $entry) {
                if (($entry['debit'] ?? 0) > 0 || ($entry['credit'] ?? 0) > 0) {
                    JournalEntry::create([
                        'journal_id' => $journal->id,
                        'account_id' => $entry['account_id'],
                        'debit' => $entry['debit'] ?? 0,
                        'credit' => $entry['credit'] ?? 0,
                        'description' => $entry['description'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('journal.index')
            ->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy(Journal $journal)
    {
        if ($journal->status !== 'draft') {
            return back()->with('error', 'Hanya jurnal dengan status draft yang dapat dihapus.');
        }

        DB::transaction(function () use ($journal) {
            $journal->entries()->delete();
            $journal->delete();
        });

        return redirect()->route('journal.index')
            ->with('success', 'Jurnal berhasil dihapus.');
    }

    public function approve(Journal $journal)
    {
        if ($journal->status !== 'draft') {
            return back()->with('error', 'Jurnal sudah diproses.');
        }

        if (!$journal->isBalanced()) {
            return back()->with('error', 'Jurnal tidak balance.');
        }

        $journal->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Jurnal berhasil disetujui.');
    }

    public function reject(Journal $journal)
    {
        if ($journal->status !== 'draft') {
            return back()->with('error', 'Jurnal sudah diproses.');
        }

        $journal->update(['status' => 'rejected']);

        return back()->with('success', 'Jurnal ditolak.');
    }
}

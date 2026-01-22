<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\FiscalPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::with(['fiscalPeriod', 'businessUnit', 'createdBy', 'items'])
            ->orderBy('created_at', 'desc');

        // Filter by fiscal period
        if ($request->filled('fiscal_period_id')) {
            $query->where('fiscal_period_id', $request->fiscal_period_id);
        }

        // Filter by business unit
        if ($request->filled('business_unit_id')) {
            $query->where('business_unit_id', $request->business_unit_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $budgets = $query->paginate(15)->withQueryString();
        $fiscalPeriods = FiscalPeriod::orderBy('start_date', 'desc')->get();
        $businessUnits = BusinessUnit::active()->get();

        return view('budget.index', compact('budgets', 'fiscalPeriods', 'businessUnits'));
    }

    public function create()
    {
        $fiscalPeriods = FiscalPeriod::orderBy('start_date', 'desc')->get();
        $businessUnits = BusinessUnit::active()->get();
        $accounts = ChartOfAccount::postable()->orderBy('code')->get();

        return view('budget.create', compact('fiscalPeriods', 'businessUnits', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'fiscal_period_id' => 'required|exists:fiscal_periods,id',
            'business_unit_id' => 'required|exists:business_units,id',
            'description' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.account_id' => 'required|exists:chart_of_accounts,id',
            'items.*.description' => 'nullable|string|max:255',
            'items.*.planned_amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $totalAmount = collect($request->items)->sum('planned_amount');

            $budget = Budget::create([
                'name' => $validated['name'],
                'fiscal_period_id' => $validated['fiscal_period_id'],
                'business_unit_id' => $validated['business_unit_id'],
                'total_amount' => $totalAmount,
                'status' => 'draft',
                'description' => $validated['description'],
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                if (($item['planned_amount'] ?? 0) > 0) {
                    BudgetItem::create([
                        'budget_id' => $budget->id,
                        'account_id' => $item['account_id'],
                        'description' => $item['description'] ?? null,
                        'planned_amount' => $item['planned_amount'],
                        'realized_amount' => 0,
                    ]);
                }
            }
        });

        return redirect()->route('budget.index')
            ->with('success', 'Anggaran berhasil dibuat.');
    }

    public function show(Budget $budget)
    {
        $budget->load(['fiscalPeriod', 'businessUnit', 'createdBy', 'approvedBy', 'items.account']);
        return view('budget.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        if ($budget->status !== 'draft') {
            return back()->with('error', 'Hanya anggaran dengan status draft yang dapat diedit.');
        }

        $budget->load('items');
        $fiscalPeriods = FiscalPeriod::orderBy('start_date', 'desc')->get();
        $businessUnits = BusinessUnit::active()->get();
        $accounts = ChartOfAccount::postable()->orderBy('code')->get();

        return view('budget.edit', compact('budget', 'fiscalPeriods', 'businessUnits', 'accounts'));
    }

    public function update(Request $request, Budget $budget)
    {
        if ($budget->status !== 'draft') {
            return back()->with('error', 'Hanya anggaran dengan status draft yang dapat diedit.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'fiscal_period_id' => 'required|exists:fiscal_periods,id',
            'business_unit_id' => 'required|exists:business_units,id',
            'description' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.account_id' => 'required|exists:chart_of_accounts,id',
            'items.*.description' => 'nullable|string|max:255',
            'items.*.planned_amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $budget, $validated) {
            $totalAmount = collect($request->items)->sum('planned_amount');

            $budget->update([
                'name' => $validated['name'],
                'fiscal_period_id' => $validated['fiscal_period_id'],
                'business_unit_id' => $validated['business_unit_id'],
                'total_amount' => $totalAmount,
                'description' => $validated['description'],
            ]);

            // Delete existing items and recreate
            $budget->items()->delete();

            foreach ($request->items as $item) {
                if (($item['planned_amount'] ?? 0) > 0) {
                    BudgetItem::create([
                        'budget_id' => $budget->id,
                        'account_id' => $item['account_id'],
                        'description' => $item['description'] ?? null,
                        'planned_amount' => $item['planned_amount'],
                        'realized_amount' => 0,
                    ]);
                }
            }
        });

        return redirect()->route('budget.index')
            ->with('success', 'Anggaran berhasil diperbarui.');
    }

    public function destroy(Budget $budget)
    {
        if ($budget->status !== 'draft') {
            return back()->with('error', 'Hanya anggaran dengan status draft yang dapat dihapus.');
        }

        DB::transaction(function () use ($budget) {
            $budget->items()->delete();
            $budget->delete();
        });

        return redirect()->route('budget.index')
            ->with('success', 'Anggaran berhasil dihapus.');
    }

    public function approve(Budget $budget)
    {
        if ($budget->status !== 'draft') {
            return back()->with('error', 'Anggaran sudah diproses.');
        }

        $budget->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Anggaran berhasil disetujui.');
    }
}

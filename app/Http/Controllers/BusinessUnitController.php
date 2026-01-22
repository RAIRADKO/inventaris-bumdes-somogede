<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use Illuminate\Http\Request;

class BusinessUnitController extends Controller
{
    public function index()
    {
        $units = BusinessUnit::withCount(['users', 'cashTransactions', 'incomeTransactions', 'expenseTransactions', 'assets'])
            ->get();
        return view('business-unit.index', compact('units'));
    }

    public function create()
    {
        return view('business-unit.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:business_units,code',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = true;
        BusinessUnit::create($validated);

        return redirect()
            ->route('business-unit.index')
            ->with('success', 'Unit usaha berhasil ditambahkan.');
    }

    public function show(BusinessUnit $businessUnit)
    {
        $businessUnit->load(['users', 'assets']);
        
        // Calculate unit performance
        $income = $businessUnit->incomeTransactions()->approved()->sum('amount');
        $expense = $businessUnit->expenseTransactions()->approved()->sum('amount');
        $profit = $income - $expense;

        return view('business-unit.show', compact('businessUnit', 'income', 'expense', 'profit'));
    }

    public function edit(BusinessUnit $businessUnit)
    {
        return view('business-unit.edit', compact('businessUnit'));
    }

    public function update(Request $request, BusinessUnit $businessUnit)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:business_units,code,' . $businessUnit->id,
            'name' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $businessUnit->update($validated);

        return redirect()
            ->route('business-unit.show', $businessUnit)
            ->with('success', 'Unit usaha berhasil diperbarui.');
    }

    public function toggleActive(BusinessUnit $businessUnit)
    {
        $businessUnit->update(['is_active' => !$businessUnit->is_active]);
        
        $status = $businessUnit->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Unit usaha berhasil {$status}.");
    }

    public function destroy(BusinessUnit $businessUnit)
    {
        // Check if unit has related data
        $hasUsers = $businessUnit->users()->exists();
        $hasAssets = $businessUnit->assets()->exists();
        $hasCashTransactions = $businessUnit->cashTransactions()->exists();
        $hasIncomeTransactions = $businessUnit->incomeTransactions()->exists();
        $hasExpenseTransactions = $businessUnit->expenseTransactions()->exists();

        if ($hasUsers || $hasAssets || $hasCashTransactions || $hasIncomeTransactions || $hasExpenseTransactions) {
            return back()->with('error', 'Unit usaha tidak dapat dihapus karena masih memiliki data terkait (pengguna, aset, atau transaksi). Nonaktifkan unit ini sebagai alternatif.');
        }

        $businessUnit->delete();

        return redirect()
            ->route('business-unit.index')
            ->with('success', 'Unit usaha berhasil dihapus.');
    }
}

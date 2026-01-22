<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = ChartOfAccount::with('parent')
            ->orderBy('code');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $accounts = $query->paginate(20)->withQueryString();

        // Get hierarchical tree for sidebar
        $accountTree = ChartOfAccount::whereNull('parent_id')
            ->with('children.children.children')
            ->orderBy('code')
            ->get();

        $types = [
            'asset' => 'Aset',
            'liability' => 'Kewajiban',
            'equity' => 'Ekuitas',
            'revenue' => 'Pendapatan',
            'expense' => 'Beban',
        ];

        return view('chart-of-account.index', compact('accounts', 'accountTree', 'types'));
    }

    public function create()
    {
        $parents = ChartOfAccount::where('is_header', true)
            ->orderBy('code')
            ->get();

        $types = [
            'asset' => 'Aset',
            'liability' => 'Kewajiban',
            'equity' => 'Ekuitas',
            'revenue' => 'Pendapatan',
            'expense' => 'Beban',
        ];

        return view('chart-of-account.create', compact('parents', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:chart_of_accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_header' => 'boolean',
            'description' => 'nullable|string|max:500',
        ]);

        // Calculate level
        $level = 1;
        if ($request->parent_id) {
            $parent = ChartOfAccount::find($request->parent_id);
            $level = $parent->level + 1;
        }

        $validated['level'] = $level;
        $validated['is_header'] = $request->boolean('is_header');
        $validated['is_active'] = true;

        ChartOfAccount::create($validated);

        return redirect()->route('chart-of-account.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        $parents = ChartOfAccount::where('is_header', true)
            ->where('id', '!=', $chartOfAccount->id)
            ->orderBy('code')
            ->get();

        $types = [
            'asset' => 'Aset',
            'liability' => 'Kewajiban',
            'equity' => 'Ekuitas',
            'revenue' => 'Pendapatan',
            'expense' => 'Beban',
        ];

        return view('chart-of-account.edit', compact('chartOfAccount', 'parents', 'types'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('chart_of_accounts')->ignore($chartOfAccount->id)],
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_header' => 'boolean',
            'description' => 'nullable|string|max:500',
        ]);

        // Calculate level
        $level = 1;
        if ($request->parent_id) {
            $parent = ChartOfAccount::find($request->parent_id);
            $level = $parent->level + 1;
        }

        $validated['level'] = $level;
        $validated['is_header'] = $request->boolean('is_header');

        $chartOfAccount->update($validated);

        return redirect()->route('chart-of-account.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        // Check if has children
        if ($chartOfAccount->children()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang memiliki sub-akun.');
        }

        // Check if used in transactions
        if ($chartOfAccount->journalEntries()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sudah digunakan dalam transaksi.');
        }

        $chartOfAccount->delete();

        return redirect()->route('chart-of-account.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    public function toggleActive(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->update(['is_active' => !$chartOfAccount->is_active]);

        $status = $chartOfAccount->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun berhasil {$status}.");
    }
}

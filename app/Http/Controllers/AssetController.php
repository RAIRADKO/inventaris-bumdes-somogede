<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'businessUnit'])
            ->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $assets = $query->paginate(20);
        $categories = AssetCategory::active()->get();

        $totalValue = Asset::active()->sum('current_value');
        $totalAssets = Asset::active()->count();

        return view('asset.index', compact('assets', 'categories', 'totalValue', 'totalAssets'));
    }

    public function create()
    {
        $categories = AssetCategory::active()->get();
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        return view('asset.create', compact('categories', 'businessUnits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:asset_categories,id',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'acquisition_date' => 'required|date',
            'acquisition_cost' => 'required|numeric|min:0',
            'salvage_value' => 'nullable|numeric|min:0',
            'condition' => 'required|in:good,fair,poor,damaged',
            'location' => 'nullable|string|max:200',
            'serial_number' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $category = AssetCategory::find($validated['category_id']);
        $validated['code'] = Asset::generateCode($category);
        $validated['salvage_value'] = $validated['salvage_value'] ?? 0;
        $validated['current_value'] = $validated['acquisition_cost'];
        $validated['accumulated_depreciation'] = 0;
        $validated['status'] = 'active';
        $validated['created_by'] = Auth::id();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('assets/photos', 'public');
        }

        $asset = Asset::create($validated);

        return redirect()
            ->route('asset.show', $asset)
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'businessUnit', 'depreciations', 'createdBy']);
        return view('asset.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $categories = AssetCategory::active()->get();
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        return view('asset.edit', compact('asset', 'categories', 'businessUnits'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:asset_categories,id',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'condition' => 'required|in:good,fair,poor,damaged',
            'location' => 'nullable|string|max:200',
            'serial_number' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('assets/photos', 'public');
        }

        $asset->update($validated);

        return redirect()
            ->route('asset.show', $asset)
            ->with('success', 'Aset berhasil diperbarui.');
    }

    public function dispose(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'disposal_date' => 'required|date',
            'disposal_value' => 'required|numeric|min:0',
            'disposal_notes' => 'required|string|max:500',
            'status' => 'required|in:disposed,sold,lost',
        ]);

        $asset->update($validated);

        return redirect()
            ->route('asset.show', $asset)
            ->with('success', 'Aset berhasil dihapuskan.');
    }

    public function categories()
    {
        $categories = AssetCategory::withCount('assets')->get();
        return view('asset.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'useful_life_years' => 'required|integer|min:1',
            'depreciation_rate' => 'required|numeric|min:0|max:100',
            'depreciation_method' => 'required|in:straight_line,declining_balance',
            'description' => 'nullable|string|max:500',
        ]);

        AssetCategory::create($validated);

        return back()->with('success', 'Kategori aset berhasil ditambahkan.');
    }
}

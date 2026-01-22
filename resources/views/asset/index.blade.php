@extends('layouts.app')

@section('title', 'Aset')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Aset & Inventaris</h1>
        <p class="text-gray-500 mt-1">Kelola aset tetap BUMDes</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('asset.categories') }}" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
            Kategori
        </a>
        <a href="{{ route('asset.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Aset
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Nilai Aset Aktif</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalValue ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Jumlah Aset Aktif</p>
        <p class="text-2xl font-bold text-primary-600 mt-1">{{ $totalAssets ?? 0 }} unit</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('asset.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select name="category_id" class="border-gray-300 rounded-lg text-sm">
            <option value="">Semua Kategori</option>
            @foreach($categories ?? [] as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="border-gray-300 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>Dihapuskan</option>
            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Dijual</option>
        </select>
        <select name="condition" class="border-gray-300 rounded-lg text-sm">
            <option value="">Semua Kondisi</option>
            <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Baik</option>
            <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Cukup</option>
            <option value="poor" {{ request('condition') == 'poor' ? 'selected' : '' }}>Buruk</option>
            <option value="damaged" {{ request('condition') == 'damaged' ? 'selected' : '' }}>Rusak</option>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-900">Filter</button>
            <a href="{{ route('asset.index') }}" class="px-4 py-2 border border-gray-300 text-sm rounded-lg hover:bg-gray-50">Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Kode</th>
                    <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Nama Aset</th>
                    <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Kondisi</th>
                    <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Nilai Buku</th>
                    <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assets ?? [] as $asset)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $asset->code }}</td>
                    <td class="py-4 px-6 text-sm text-gray-900">{{ $asset->name }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $asset->category?->name }}</td>
                    <td class="py-4 px-6">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $asset->condition == 'good' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $asset->condition == 'fair' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $asset->condition == 'poor' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $asset->condition == 'damaged' ? 'bg-red-100 text-red-700' : '' }}
                        ">{{ ucfirst($asset->condition) }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $asset->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}
                        ">{{ ucfirst($asset->status) }}</span>
                    </td>
                    <td class="py-4 px-6 text-sm text-right font-semibold text-gray-900">
                        Rp {{ number_format($asset->current_value, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('asset.show', $asset) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">Lihat</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-500">Belum ada data aset</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($assets) && $assets->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $assets->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

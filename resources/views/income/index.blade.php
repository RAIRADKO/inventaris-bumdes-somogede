@extends('layouts.app')

@section('title', 'Pemasukan')
@section('subtitle', 'Kelola transaksi pendapatan')

@section('content')
<!-- Summary -->
<div class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-8 mb-8 shadow-soft-lg relative overflow-hidden">
    <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -mr-24 -mt-24"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-400/20 rounded-full -ml-16 -mb-16"></div>
    <div class="relative">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <p class="text-blue-100 font-medium mb-2">Total Pemasukan (Approved)</p>
                <p class="text-4xl font-bold text-white">Rp {{ number_format($totalApproved ?? 0, 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('income.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-white text-blue-700 font-bold rounded-xl hover:bg-blue-50 transition-all shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Pemasukan
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-5 mb-6">
    <form action="{{ route('income.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kategori</label>
            <select name="category_id" class="w-full border-gray-200 rounded-xl text-sm py-2.5 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua</option>
                @foreach($categories ?? [] as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Unit</label>
            <select name="business_unit_id" class="w-full border-gray-200 rounded-xl text-sm py-2.5 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua</option>
                @foreach($businessUnits ?? [] as $unit)
                <option value="{{ $unit->id }}" {{ request('business_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status</label>
            <select name="status" class="w-full border-gray-200 rounded-xl text-sm py-2.5 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dari</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border-gray-200 rounded-xl text-sm py-2.5">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sampai</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border-gray-200 rounded-xl text-sm py-2.5">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-900">Filter</button>
            <a href="{{ route('income.index') }}" class="px-3 py-2.5 border border-gray-200 text-gray-500 rounded-xl hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase">No. Transaksi</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase">Tanggal</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase">Kategori</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase">Keterangan</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase">Status</th>
                    <th class="text-right py-4 px-6 text-xs font-bold text-gray-400 uppercase">Jumlah</th>
                    <th class="text-center py-4 px-6 text-xs font-bold text-gray-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($transactions ?? [] as $trx)
                <tr class="table-row-hover">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">{{ $trx->transaction_number }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $trx->date->format('d M Y') }}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg">{{ $trx->category?->name ?? '-' }}</span>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600 max-w-xs truncate">{{ $trx->description }}</td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold
                            {{ $trx->status == 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $trx->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $trx->status == 'draft' ? 'bg-gray-100 text-gray-600' : '' }}
                            {{ $trx->status == 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}
                        ">{{ ucfirst($trx->status) }}</span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <span class="text-sm font-bold text-blue-600">+Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('income.show', $trx) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg hover:bg-blue-100 hover:text-blue-700 transition">
                            Lihat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-semibold mb-1">Belum ada transaksi pemasukan</p>
                            <p class="text-gray-400 text-sm mb-4">Catat pemasukan pertama Anda</p>
                            <a href="{{ route('income.create') }}" class="btn-primary inline-flex items-center px-5 py-2.5 text-white font-semibold rounded-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Pemasukan
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($transactions) && $transactions->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">{{ $transactions->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

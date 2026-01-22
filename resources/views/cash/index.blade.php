@extends('layouts.app')

@section('title', 'Transaksi Kas')
@section('subtitle', 'Kelola kas masuk dan kas keluar')

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-soft-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative">
            <p class="text-emerald-100 text-sm font-medium mb-1">Total Kas Masuk</p>
            <p class="text-3xl font-bold">Rp {{ number_format($totalIn ?? 0, 0, ',', '.') }}</p>
            <div class="flex items-center mt-4 text-emerald-100">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                </svg>
                <span class="text-sm">Penerimaan</span>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-6 text-white shadow-soft-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative">
            <p class="text-rose-100 text-sm font-medium mb-1">Total Kas Keluar</p>
            <p class="text-3xl font-bold">Rp {{ number_format($totalOut ?? 0, 0, ',', '.') }}</p>
            <div class="flex items-center mt-4 text-rose-100">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
                <span class="text-sm">Pengeluaran</span>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-soft-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
        <div class="relative">
            <p class="text-slate-300 text-sm font-medium mb-1">Saldo Kas</p>
            <p class="text-3xl font-bold {{ ($balance ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
            </p>
            <div class="flex items-center mt-4 text-slate-400">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
                </svg>
                <span class="text-sm">Saldo saat ini</span>
            </div>
        </div>
    </div>
</div>

<!-- Actions & Filters -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
    <a href="{{ route('cash.create') }}" 
       class="btn-primary inline-flex items-center justify-center px-6 py-3 text-white font-semibold rounded-xl shadow-lg">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Transaksi
    </a>
    
    <a href="{{ route('cash.daily-report') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-medium rounded-xl hover:bg-gray-50 transition shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Laporan Harian
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-5 mb-6">
    <form action="{{ route('cash.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tipe</label>
            <select name="type" class="w-full border-gray-200 rounded-xl text-sm focus:ring-primary-500 focus:border-primary-500 py-2.5">
                <option value="">Semua Tipe</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Kas Masuk</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Kas Keluar</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status</label>
            <select name="status" class="w-full border-gray-200 rounded-xl text-sm focus:ring-primary-500 focus:border-primary-500 py-2.5">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-primary-500 focus:border-primary-500 py-2.5">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" 
                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-primary-500 focus:border-primary-500 py-2.5">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-5 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-900 transition">
                Filter
            </button>
            <a href="{{ route('cash.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                Reset
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
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">No. Transaksi</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Tipe</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-right py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
                    <th class="text-center py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($transactions ?? [] as $trx)
                <tr class="table-row-hover">
                    <td class="py-4 px-6">
                        <span class="text-sm font-semibold text-gray-800">{{ $trx->transaction_number }}</span>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $trx->date->format('d M Y') }}</td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold 
                            {{ $trx->type == 'in' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="{{ $trx->type == 'in' ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}"/>
                            </svg>
                            {{ $trx->type == 'in' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600 max-w-xs truncate">{{ $trx->description }}</td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold
                            {{ $trx->status == 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $trx->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $trx->status == 'draft' ? 'bg-gray-100 text-gray-600' : '' }}
                            {{ $trx->status == 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}
                        ">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <span class="text-sm font-bold {{ $trx->type == 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $trx->type == 'in' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('cash.show', $trx) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg hover:bg-primary-100 hover:text-primary-700 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-semibold mb-1">Belum ada transaksi kas</p>
                            <p class="text-gray-400 text-sm mb-4">Mulai catat transaksi kas pertama Anda</p>
                            <a href="{{ route('cash.create') }}" class="btn-primary inline-flex items-center px-5 py-2.5 text-white font-semibold rounded-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Transaksi
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($transactions) && $transactions->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $transactions->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan keuangan BUMDes')

@section('content')
<!-- Welcome Banner -->
<div class="relative overflow-hidden bg-gradient-to-br from-primary-500 via-primary-600 to-emerald-700 rounded-3xl p-8 mb-8 shadow-soft-lg">
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-64 h-64 bg-primary-400/20 rounded-full blur-2xl"></div>
    <div class="relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-6 lg:mb-0">
                <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">
                    Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}! 👋
                </h1>
                <p class="text-primary-100 text-lg">Berikut adalah ringkasan keuangan BUMDes Somogede hari ini.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('income.create') }}" class="inline-flex items-center px-5 py-3 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-xl hover:bg-white/30 transition-all border border-white/20">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Catat Pemasukan
                </a>
                <a href="{{ route('expense.create') }}" class="inline-flex items-center px-5 py-3 bg-white text-primary-700 font-semibold rounded-xl hover:bg-primary-50 transition-all shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Catat Pengeluaran
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Saldo Kas -->
    <div class="group bg-white rounded-2xl p-6 shadow-soft border border-gray-100/50 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200/50">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-full">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                    Live
                </span>
            </div>
            <p class="text-sm font-medium text-gray-400 mb-1">Saldo Kas</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-800">Rp {{ number_format($cashBalance ?? 0, 0, ',', '.') }}</p>
            <a href="{{ route('cash.index') }}" class="inline-flex items-center mt-4 text-sm text-emerald-600 hover:text-emerald-700 font-semibold group/link">
                Lihat Detail
                <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Pendapatan Bulan Ini -->
    <div class="group bg-white rounded-2xl p-6 shadow-soft border border-gray-100/50 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-blue-50 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200/50">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-full">Bulan Ini</span>
            </div>
            <p class="text-sm font-medium text-gray-400 mb-1">Pendapatan</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-800">Rp {{ number_format($monthlyIncome ?? 0, 0, ',', '.') }}</p>
            <a href="{{ route('income.index') }}" class="inline-flex items-center mt-4 text-sm text-blue-600 hover:text-blue-700 font-semibold group/link">
                Lihat Detail
                <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Pengeluaran Bulan Ini -->
    <div class="group bg-white rounded-2xl p-6 shadow-soft border border-gray-100/50 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-rose-100 to-rose-50 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg shadow-rose-200/50">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                </div>
                <span class="px-2.5 py-1 bg-rose-50 text-rose-600 text-xs font-bold rounded-full">Bulan Ini</span>
            </div>
            <p class="text-sm font-medium text-gray-400 mb-1">Pengeluaran</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-800">Rp {{ number_format($monthlyExpense ?? 0, 0, ',', '.') }}</p>
            <a href="{{ route('expense.index') }}" class="inline-flex items-center mt-4 text-sm text-rose-600 hover:text-rose-700 font-semibold group/link">
                Lihat Detail
                <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Laba/Rugi -->
    <div class="group bg-white rounded-2xl p-6 shadow-soft border border-gray-100/50 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br {{ ($profitLoss ?? 0) >= 0 ? 'from-primary-100 to-primary-50' : 'from-red-100 to-red-50' }} rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br {{ ($profitLoss ?? 0) >= 0 ? 'from-primary-400 to-primary-600 shadow-primary-200/50' : 'from-red-400 to-red-600 shadow-red-200/50' }} rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="px-2.5 py-1 {{ ($profitLoss ?? 0) >= 0 ? 'bg-primary-50 text-primary-600' : 'bg-red-50 text-red-600' }} text-xs font-bold rounded-full">
                    {{ ($profitLoss ?? 0) >= 0 ? 'Laba' : 'Rugi' }}
                </span>
            </div>
            <p class="text-sm font-medium text-gray-400 mb-1">Laba/Rugi Bulan Ini</p>
            <p class="text-2xl lg:text-3xl font-bold {{ ($profitLoss ?? 0) >= 0 ? 'text-primary-600' : 'text-red-600' }}">
                Rp {{ number_format(abs($profitLoss ?? 0), 0, ',', '.') }}
            </p>
            <a href="{{ route('report.income-statement') }}" class="inline-flex items-center mt-4 text-sm text-gray-500 hover:text-gray-700 font-semibold group/link">
                Lihat Laporan
                <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Second Row - Mini Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Piutang -->
    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100/50 shadow-soft">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            @if(($overdueReceivables ?? 0) > 0)
            <span class="flex items-center px-2.5 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full animate-pulse">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Jatuh Tempo
            </span>
            @endif
        </div>
        <h3 class="text-sm font-semibold text-amber-800 mb-1">Total Piutang</h3>
        <p class="text-3xl font-bold text-amber-900 mb-2">Rp {{ number_format($totalReceivables ?? 0, 0, ',', '.') }}</p>
        @if(($overdueReceivables ?? 0) > 0)
        <p class="text-sm text-red-600 font-medium">
            Rp {{ number_format($overdueReceivables, 0, ',', '.') }} jatuh tempo
        </p>
        @else
        <p class="text-sm text-amber-600">Semua lancar ✓</p>
        @endif
    </div>

    <!-- Hutang -->
    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-100/50 shadow-soft">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <h3 class="text-sm font-semibold text-orange-800 mb-1">Total Hutang</h3>
        <p class="text-3xl font-bold text-orange-900 mb-2">Rp {{ number_format($totalPayables ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm text-orange-600">Belum dilunasi</p>
    </div>

    <!-- Total Aset -->
    <div class="bg-gradient-to-br from-violet-50 to-purple-50 rounded-2xl p-6 border border-violet-100/50 shadow-soft">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-violet-400 to-purple-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <span class="px-2.5 py-1 bg-violet-100 text-violet-600 text-xs font-bold rounded-full">Nilai Buku</span>
        </div>
        <h3 class="text-sm font-semibold text-violet-800 mb-1">Total Nilai Aset</h3>
        <p class="text-3xl font-bold text-violet-900 mb-2">Rp {{ number_format($totalAssetValue ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm text-violet-600">Aset aktif saat ini</p>
    </div>
</div>

<!-- Chart & Pending -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-soft border border-gray-100/50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Tren Keuangan</h3>
                <p class="text-sm text-gray-400">6 bulan terakhir</p>
            </div>
            <div class="flex items-center space-x-4 text-sm">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-emerald-500 rounded-full mr-2"></span>
                    <span class="text-gray-500">Pendapatan</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-rose-500 rounded-full mr-2"></span>
                    <span class="text-gray-500">Pengeluaran</span>
                </div>
            </div>
        </div>
        <div class="h-72">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100/50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Menunggu Persetujuan</h3>
                <p class="text-sm text-gray-400">Transaksi pending</p>
            </div>
            @if(($pendingApprovals['total'] ?? 0) > 0)
            <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-sm font-bold animate-pulse">
                {{ $pendingApprovals['total'] }}
            </span>
            @endif
        </div>
        
        @if(($pendingApprovals['total'] ?? 0) > 0)
        <div class="space-y-3">
            @if(($pendingApprovals['income'] ?? 0) > 0)
            <a href="{{ route('income.index', ['status' => 'pending']) }}" 
               class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl hover:shadow-md transition-all group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-blue-700">Pemasukan</span>
                </div>
                <span class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm">{{ $pendingApprovals['income'] }}</span>
            </a>
            @endif
            
            @if(($pendingApprovals['expense'] ?? 0) > 0)
            <a href="{{ route('expense.index', ['status' => 'pending']) }}" 
               class="flex items-center justify-between p-4 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl hover:shadow-md transition-all group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-rose-700">Pengeluaran</span>
                </div>
                <span class="px-3 py-1.5 bg-rose-600 text-white text-xs font-bold rounded-lg shadow-sm">{{ $pendingApprovals['expense'] }}</span>
            </a>
            @endif
            
            @if(($pendingApprovals['cash'] ?? 0) > 0)
            <a href="{{ route('cash.index', ['status' => 'pending']) }}" 
               class="flex items-center justify-between p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl hover:shadow-md transition-all group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-emerald-700">Transaksi Kas</span>
                </div>
                <span class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg shadow-sm">{{ $pendingApprovals['cash'] }}</span>
            </a>
            @endif
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12 text-gray-400">
            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="font-medium text-gray-500">Semua sudah diproses</p>
            <p class="text-sm text-gray-400">Tidak ada transaksi pending</p>
        </div>
        @endif
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Transaksi Terakhir</h3>
                <p class="text-sm text-gray-400">Aktivitas terbaru</p>
            </div>
            <a href="{{ route('cash.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-50 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-100 transition">
                Lihat Semua
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</th>
                    <th class="text-left py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-right py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentTransactions ?? [] as $trx)
                <tr class="table-row-hover transition-colors">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 {{ $trx['type'] === 'income' ? 'bg-emerald-100' : 'bg-rose-100' }} rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 {{ $trx['type'] === 'income' ? 'text-emerald-600' : 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $trx['type'] === 'income' ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($trx['date'])->format('d M Y') }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600 max-w-xs">{{ Str::limit($trx['description'], 40) }}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg">{{ $trx['category'] ?? '-' }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold
                            {{ $trx['status'] === 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $trx['status'] === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $trx['status'] === 'draft' ? 'bg-gray-100 text-gray-600' : '' }}
                            {{ $trx['status'] === 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}
                        ">
                            @if($trx['status'] === 'approved')
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            @endif
                            {{ ucfirst($trx['status']) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <span class="text-sm font-bold {{ $trx['type'] === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $trx['type'] === 'income' ? '+' : '-' }} Rp {{ number_format($trx['amount'], 0, ',', '.') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                            <p class="text-gray-400 text-sm mb-4">Mulai catat transaksi pertama Anda</p>
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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('trendChart').getContext('2d');
    const monthlyTrend = @json($monthlyTrend ?? []);
    
    // Create gradient
    const incomeGradient = ctx.createLinearGradient(0, 0, 0, 300);
    incomeGradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
    incomeGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
    
    const expenseGradient = ctx.createLinearGradient(0, 0, 0, 300);
    expenseGradient.addColorStop(0, 'rgba(244, 63, 94, 0.3)');
    expenseGradient.addColorStop(1, 'rgba(244, 63, 94, 0)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyTrend.map(item => item.month),
            datasets: [
                {
                    label: 'Pendapatan',
                    data: monthlyTrend.map(item => item.income),
                    borderColor: '#10b981',
                    backgroundColor: incomeGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                },
                {
                    label: 'Pengeluaran',
                    data: monthlyTrend.map(item => item.expense),
                    borderColor: '#f43f5e',
                    backgroundColor: expenseGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f43f5e',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { 
                        font: { size: 12, weight: '500' },
                        color: '#94a3b8'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { 
                        color: 'rgba(148, 163, 184, 0.1)',
                        drawBorder: false,
                    },
                    ticks: {
                        font: { size: 11 },
                        color: '#94a3b8',
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + ' Jt';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + ' Rb';
                            }
                            return value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('subtitle', 'Pilih jenis laporan yang ingin dilihat')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Laba Rugi -->
    <a href="{{ route('report.income-statement') }}" 
       class="group bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-blue-50 rounded-full -mr-16 -mt-16 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-200/50 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-2 group-hover:text-blue-600 transition">Laporan Laba Rugi</h3>
            <p class="text-sm text-gray-500 mb-4">Pendapatan, beban, dan laba/rugi bersih periode</p>
            <span class="inline-flex items-center text-sm font-semibold text-blue-600 group-hover:translate-x-2 transition-transform">
                Lihat Laporan
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </a>

    <!-- Neraca -->
    <a href="{{ route('report.balance-sheet') }}" 
       class="group bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-full -mr-16 -mt-16 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-200/50 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-2 group-hover:text-emerald-600 transition">Neraca</h3>
            <p class="text-sm text-gray-500 mb-4">Posisi aset, kewajiban, dan ekuitas</p>
            <span class="inline-flex items-center text-sm font-semibold text-emerald-600 group-hover:translate-x-2 transition-transform">
                Lihat Laporan
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </a>

    <!-- Arus Kas -->
    <a href="{{ route('report.cash-flow') }}" 
       class="group bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-purple-50 rounded-full -mr-16 -mt-16 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-purple-200/50 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-2 group-hover:text-purple-600 transition">Arus Kas</h3>
            <p class="text-sm text-gray-500 mb-4">Arus kas operasi, investasi, dan pendanaan</p>
            <span class="inline-flex items-center text-sm font-semibold text-purple-600 group-hover:translate-x-2 transition-transform">
                Lihat Laporan
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </a>

    <!-- Buku Besar -->
    <a href="{{ route('report.general-ledger') }}" 
       class="group bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-100 to-amber-50 rounded-full -mr-16 -mt-16 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-amber-200/50 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-2 group-hover:text-amber-600 transition">Buku Besar</h3>
            <p class="text-sm text-gray-500 mb-4">Detail transaksi per akun</p>
            <span class="inline-flex items-center text-sm font-semibold text-amber-600 group-hover:translate-x-2 transition-transform">
                Lihat Laporan
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </a>

    <!-- Neraca Saldo -->
    <a href="{{ route('report.trial-balance') }}" 
       class="group bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-full -mr-16 -mt-16 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-indigo-200/50 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-2 group-hover:text-indigo-600 transition">Neraca Saldo</h3>
            <p class="text-sm text-gray-500 mb-4">Saldo semua akun (debit/kredit)</p>
            <span class="inline-flex items-center text-sm font-semibold text-indigo-600 group-hover:translate-x-2 transition-transform">
                Lihat Laporan
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </a>

    <!-- Aging Piutang -->
    <a href="{{ route('receivable.aging') }}" 
       class="group bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6 card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-rose-100 to-rose-50 rounded-full -mr-16 -mt-16 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-16 h-16 bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-rose-200/50 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-2 group-hover:text-rose-600 transition">Aging Piutang</h3>
            <p class="text-sm text-gray-500 mb-4">Umur piutang berdasarkan jatuh tempo</p>
            <span class="inline-flex items-center text-sm font-semibold text-rose-600 group-hover:translate-x-2 transition-transform">
                Lihat Laporan
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </a>
</div>
@endsection

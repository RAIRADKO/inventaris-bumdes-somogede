@extends('layouts.app')

@section('title', 'Laporan Arus Kas')

@section('content')
<div class="mb-6">
    <a href="{{ route('report.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Laporan Arus Kas</h1>
    <p class="text-gray-500 mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
</div>

<!-- Filter -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('report.cash-flow') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border-gray-300 rounded-lg text-sm">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900">
                Tampilkan
            </button>
        </div>
    </form>
</div>

<!-- Report -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900 text-center">BUMDES SOMOGEDE</h2>
        <p class="text-center text-gray-500">Laporan Arus Kas</p>
        <p class="text-center text-sm text-gray-400">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <div class="p-6 space-y-6">
        <!-- Aktivitas Operasi -->
        <div>
            <h3 class="font-semibold text-gray-900 bg-green-50 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                ARUS KAS DARI AKTIVITAS OPERASI
            </h3>
            <table class="w-full mt-2">
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm text-gray-900">Penerimaan dari Pendapatan Usaha</td>
                    <td class="py-3 px-4 text-sm text-right text-green-600 font-medium">Rp {{ number_format($operatingIncome ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm text-gray-900">Pembayaran Beban Operasional</td>
                    <td class="py-3 px-4 text-sm text-right text-red-600 font-medium">(Rp {{ number_format($operatingExpense ?? 0, 0, ',', '.') }})</td>
                </tr>
                <tr class="font-semibold border-t-2 border-green-200 bg-green-50">
                    <td class="py-3 px-4 text-green-900">Arus Kas Bersih dari Aktivitas Operasi</td>
                    <td class="py-3 px-4 text-right {{ ($netOperating ?? 0) >= 0 ? 'text-green-700' : 'text-red-600' }}">
                        Rp {{ number_format($netOperating ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Aktivitas Investasi -->
        <div>
            <h3 class="font-semibold text-gray-900 bg-blue-50 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                ARUS KAS DARI AKTIVITAS INVESTASI
            </h3>
            <table class="w-full mt-2">
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm text-gray-900">Penerimaan dari Penjualan Aset</td>
                    <td class="py-3 px-4 text-sm text-right text-green-600 font-medium">Rp {{ number_format($assetSales ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm text-gray-900">Pembelian Aset Tetap</td>
                    <td class="py-3 px-4 text-sm text-right text-red-600 font-medium">(Rp {{ number_format($assetPurchases ?? 0, 0, ',', '.') }})</td>
                </tr>
                <tr class="font-semibold border-t-2 border-blue-200 bg-blue-50">
                    <td class="py-3 px-4 text-blue-900">Arus Kas Bersih dari Aktivitas Investasi</td>
                    <td class="py-3 px-4 text-right {{ ($investingActivities ?? 0) >= 0 ? 'text-blue-700' : 'text-red-600' }}">
                        Rp {{ number_format($investingActivities ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Aktivitas Pendanaan -->
        <div>
            <h3 class="font-semibold text-gray-900 bg-purple-50 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                ARUS KAS DARI AKTIVITAS PENDANAAN
            </h3>
            <table class="w-full mt-2">
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm text-gray-900">Penerimaan Modal/Penyertaan</td>
                    <td class="py-3 px-4 text-sm text-right text-green-600 font-medium">Rp {{ number_format($capitalIn ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm text-gray-900">Pembagian SHU/Dividen</td>
                    <td class="py-3 px-4 text-sm text-right text-red-600 font-medium">(Rp {{ number_format($capitalOut ?? 0, 0, ',', '.') }})</td>
                </tr>
                <tr class="font-semibold border-t-2 border-purple-200 bg-purple-50">
                    <td class="py-3 px-4 text-purple-900">Arus Kas Bersih dari Aktivitas Pendanaan</td>
                    <td class="py-3 px-4 text-right {{ ($financingActivities ?? 0) >= 0 ? 'text-purple-700' : 'text-red-600' }}">
                        Rp {{ number_format($financingActivities ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Total Arus Kas -->
        <div class="bg-gray-900 text-white rounded-lg p-4">
            <div class="flex justify-between items-center">
                <span class="font-semibold text-lg">KENAIKAN/PENURUNAN KAS BERSIH</span>
                <span class="text-2xl font-bold {{ ($netCashFlow ?? 0) >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    Rp {{ number_format($netCashFlow ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="p-4 bg-green-50 rounded-xl border border-green-200">
                <p class="text-sm text-green-600 font-medium">Aktivitas Operasi</p>
                <p class="text-xl font-bold {{ ($netOperating ?? 0) >= 0 ? 'text-green-700' : 'text-red-600' }}">
                    Rp {{ number_format($netOperating ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                <p class="text-sm text-blue-600 font-medium">Aktivitas Investasi</p>
                <p class="text-xl font-bold {{ ($investingActivities ?? 0) >= 0 ? 'text-blue-700' : 'text-red-600' }}">
                    Rp {{ number_format($investingActivities ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                <p class="text-sm text-purple-600 font-medium">Aktivitas Pendanaan</p>
                <p class="text-xl font-bold {{ ($financingActivities ?? 0) >= 0 ? 'text-purple-700' : 'text-red-600' }}">
                    Rp {{ number_format($financingActivities ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

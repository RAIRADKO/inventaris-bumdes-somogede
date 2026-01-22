@extends('layouts.app')

@section('title', 'Laporan Neraca')

@section('content')
<div class="mb-6">
    <a href="{{ route('report.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Laporan Neraca</h1>
    <p class="text-gray-500 mt-1">Per Tanggal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
</div>

<!-- Filter -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('report.balance-sheet') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Per Tanggal</label>
            <input type="date" name="date" value="{{ $date }}" class="w-full border-gray-300 rounded-lg text-sm">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900">
                Tampilkan
            </button>
        </div>
        <div class="flex items-end gap-2">
            <a href="{{ route('report.balance-sheet.excel', ['date' => $date]) }}" 
               class="flex-1 px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 text-center flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Excel
            </a>
            <a href="{{ route('report.balance-sheet.pdf', ['date' => $date]) }}" 
               class="flex-1 px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 text-center flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                PDF
            </a>
        </div>
    </form>
</div>

<!-- Report -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900 text-center">BUMDES SOMOGEDE</h2>
        <p class="text-center text-gray-500">Laporan Neraca</p>
        <p class="text-center text-sm text-gray-400">Per {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Aset -->
            <div>
                <h3 class="font-semibold text-gray-900 bg-blue-50 px-4 py-2 rounded-lg mb-2">ASET</h3>
                <table class="w-full">
                    @forelse($assets ?? [] as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 text-sm text-gray-600">{{ $account->code }}</td>
                        <td class="py-2 px-4 text-sm text-gray-900">{{ $account->name }}</td>
                        <td class="py-2 px-4 text-sm text-right text-gray-900">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 px-4 text-center text-gray-400 text-sm">Tidak ada data</td>
                    </tr>
                    @endforelse
                    <tr class="font-semibold border-t-2 border-blue-200 bg-blue-50">
                        <td colspan="2" class="py-3 px-4 text-blue-900">Total Aset</td>
                        <td class="py-3 px-4 text-right text-blue-700">Rp {{ number_format($totalAssets ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Kewajiban & Ekuitas -->
            <div>
                <!-- Kewajiban -->
                <h3 class="font-semibold text-gray-900 bg-red-50 px-4 py-2 rounded-lg mb-2">KEWAJIBAN</h3>
                <table class="w-full mb-4">
                    @forelse($liabilities ?? [] as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 text-sm text-gray-600">{{ $account->code }}</td>
                        <td class="py-2 px-4 text-sm text-gray-900">{{ $account->name }}</td>
                        <td class="py-2 px-4 text-sm text-right text-gray-900">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 px-4 text-center text-gray-400 text-sm">Tidak ada data</td>
                    </tr>
                    @endforelse
                    <tr class="font-semibold border-t border-red-200">
                        <td colspan="2" class="py-2 px-4 text-red-900">Total Kewajiban</td>
                        <td class="py-2 px-4 text-right text-red-600">Rp {{ number_format($totalLiabilities ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <!-- Ekuitas -->
                <h3 class="font-semibold text-gray-900 bg-purple-50 px-4 py-2 rounded-lg mb-2">EKUITAS</h3>
                <table class="w-full">
                    @forelse($equities ?? [] as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 text-sm text-gray-600">{{ $account->code }}</td>
                        <td class="py-2 px-4 text-sm text-gray-900">{{ $account->name }}</td>
                        <td class="py-2 px-4 text-sm text-right text-gray-900">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 px-4 text-center text-gray-400 text-sm">Tidak ada data</td>
                    </tr>
                    @endforelse
                    <tr class="font-semibold border-t border-purple-200">
                        <td colspan="2" class="py-2 px-4 text-purple-900">Total Ekuitas</td>
                        <td class="py-2 px-4 text-right text-purple-600">Rp {{ number_format($totalEquity ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <!-- Total Kewajiban + Ekuitas -->
                <div class="mt-4 bg-gray-900 text-white rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">KEWAJIBAN + EKUITAS</span>
                        <span class="text-lg font-bold">Rp {{ number_format(($totalLiabilities ?? 0) + ($totalEquity ?? 0), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Check -->
        @php
            $isBalanced = abs(($totalAssets ?? 0) - (($totalLiabilities ?? 0) + ($totalEquity ?? 0))) < 0.01;
        @endphp
        <div class="mt-6 p-4 rounded-lg {{ $isBalanced ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
            <div class="flex items-center justify-center">
                @if($isBalanced)
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-700 font-medium">Neraca Seimbang (Balance)</span>
                @else
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-red-700 font-medium">Neraca Tidak Seimbang - Selisih: Rp {{ number_format(abs(($totalAssets ?? 0) - (($totalLiabilities ?? 0) + ($totalEquity ?? 0))), 0, ',', '.') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

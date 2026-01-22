@extends('layouts.app')

@section('title', 'Neraca Saldo')

@section('content')
<div class="mb-6">
    <a href="{{ route('report.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Neraca Saldo</h1>
    <p class="text-gray-500 mt-1">Per Tanggal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
</div>

<!-- Filter -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('report.trial-balance') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
            <a href="{{ route('report.trial-balance.excel', ['date' => $date]) }}" 
               class="flex-1 px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 text-center flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Excel
            </a>
            <a href="{{ route('report.trial-balance.pdf', ['date' => $date]) }}" 
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
        <p class="text-center text-gray-500">Neraca Saldo (Trial Balance)</p>
        <p class="text-center text-sm text-gray-400">Per {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Akun</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Debit</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Kredit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $currentType = null;
                    $typeLabels = [
                        'asset' => 'ASET',
                        'liability' => 'KEWAJIBAN',
                        'equity' => 'EKUITAS',
                        'revenue' => 'PENDAPATAN',
                        'expense' => 'BEBAN',
                    ];
                    $typeColors = [
                        'asset' => 'bg-blue-50 text-blue-800',
                        'liability' => 'bg-red-50 text-red-800',
                        'equity' => 'bg-purple-50 text-purple-800',
                        'revenue' => 'bg-green-50 text-green-800',
                        'expense' => 'bg-orange-50 text-orange-800',
                    ];
                @endphp
                @forelse($accounts as $account)
                    @if($currentType !== $account->type)
                        @php $currentType = $account->type; @endphp
                        <tr class="{{ $typeColors[$currentType] ?? 'bg-gray-50' }}">
                            <td colspan="4" class="px-6 py-2 font-semibold text-sm">
                                {{ $typeLabels[$currentType] ?? strtoupper($currentType) }}
                            </td>
                        </tr>
                    @endif
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm font-mono text-gray-600">{{ $account->code }}</td>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ $account->name }}</td>
                        <td class="px-6 py-3 text-sm text-right {{ $account->debit_balance > 0 ? 'text-gray-900 font-medium' : 'text-gray-400' }}">
                            {{ $account->debit_balance > 0 ? 'Rp ' . number_format($account->debit_balance, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-right {{ $account->credit_balance > 0 ? 'text-gray-900 font-medium' : 'text-gray-400' }}">
                            {{ $account->credit_balance > 0 ? 'Rp ' . number_format($account->credit_balance, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm">Tidak ada data untuk ditampilkan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-900 text-white">
                <tr class="font-semibold">
                    <td colspan="2" class="px-6 py-4">TOTAL</td>
                    <td class="px-6 py-4 text-right text-lg">Rp {{ number_format($totalDebit ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right text-lg">Rp {{ number_format($totalCredit ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Balance Check -->
    @php
        $isBalanced = abs(($totalDebit ?? 0) - ($totalCredit ?? 0)) < 0.01;
    @endphp
    <div class="p-4 {{ $isBalanced ? 'bg-green-50' : 'bg-red-50' }}">
        <div class="flex items-center justify-center">
            @if($isBalanced)
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-green-700 font-medium">Neraca Saldo Seimbang (Balance)</span>
            @else
                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-red-700 font-medium">Neraca Saldo Tidak Seimbang - Selisih: Rp {{ number_format(abs(($totalDebit ?? 0) - ($totalCredit ?? 0)), 0, ',', '.') }}</span>
            @endif
        </div>
    </div>
</div>

<!-- Summary -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Saldo Debit</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalDebit ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Saldo Kredit</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalCredit ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
            </div>
        </div>
    </div>
</div>
@endsection

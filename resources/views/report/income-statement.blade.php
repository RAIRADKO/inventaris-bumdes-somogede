@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="mb-6">
    <a href="{{ route('report.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Laporan Laba Rugi</h1>
    <p class="text-gray-500 mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
</div>

<!-- Filter -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('report.income-statement') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
        <p class="text-center text-gray-500">Laporan Laba Rugi</p>
        <p class="text-center text-sm text-gray-400">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <div class="p-6">
        <!-- Pendapatan -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 bg-gray-50 px-4 py-2 rounded-lg">PENDAPATAN</h3>
            <table class="w-full mt-2">
                @foreach($revenues ?? [] as $account)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 text-sm text-gray-600">{{ $account->code }}</td>
                    <td class="py-2 px-4 text-sm text-gray-900">{{ $account->name }}</td>
                    <td class="py-2 px-4 text-sm text-right text-gray-900">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="font-semibold border-t border-gray-200">
                    <td colspan="2" class="py-2 px-4 text-gray-900">Total Pendapatan</td>
                    <td class="py-2 px-4 text-right text-green-600">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Beban -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 bg-gray-50 px-4 py-2 rounded-lg">BEBAN</h3>
            <table class="w-full mt-2">
                @foreach($expenses ?? [] as $account)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 text-sm text-gray-600">{{ $account->code }}</td>
                    <td class="py-2 px-4 text-sm text-gray-900">{{ $account->name }}</td>
                    <td class="py-2 px-4 text-sm text-right text-gray-900">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="font-semibold border-t border-gray-200">
                    <td colspan="2" class="py-2 px-4 text-gray-900">Total Beban</td>
                    <td class="py-2 px-4 text-right text-red-600">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Net Income -->
        <div class="bg-gray-900 text-white rounded-lg p-4">
            <div class="flex justify-between items-center">
                <span class="font-semibold text-lg">LABA/RUGI BERSIH</span>
                <span class="text-2xl font-bold {{ ($netIncome ?? 0) >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    Rp {{ number_format($netIncome ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

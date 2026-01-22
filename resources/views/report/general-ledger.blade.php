@extends('layouts.app')

@section('title', 'Buku Besar')

@section('content')
<div class="mb-6">
    <a href="{{ route('report.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Buku Besar</h1>
    <p class="text-gray-500 mt-1">Rincian transaksi per akun</p>
</div>

<!-- Filter -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('report.general-ledger') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Pilih Akun</label>
            <select name="account_id" class="w-full border-gray-300 rounded-lg text-sm">
                <option value="">-- Pilih Akun --</option>
                @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                        {{ $acc->code }} - {{ $acc->name }}
                    </option>
                @endforeach
            </select>
        </div>
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

@if($account)
<!-- Report -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900 text-center">BUMDES SOMOGEDE</h2>
        <p class="text-center text-gray-500">Buku Besar</p>
        <p class="text-center text-sm text-gray-400">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <!-- Account Info -->
    <div class="p-4 bg-gray-50 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Akun</p>
                <p class="font-semibold text-gray-900">{{ $account->code }} - {{ $account->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Saldo Normal</p>
                <span class="px-2 py-1 text-xs font-medium rounded-lg {{ $account->normal_balance === 'debit' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                    {{ $account->normal_balance === 'debit' ? 'Debit' : 'Kredit' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Ledger Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No. Jurnal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Debit</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Kredit</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Saldo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $runningBalance = 0;
                @endphp
                @forelse($entries as $entry)
                @php
                    if ($account->normal_balance === 'debit') {
                        $runningBalance += $entry->debit - $entry->credit;
                    } else {
                        $runningBalance += $entry->credit - $entry->debit;
                    }
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $entry->journal?->date?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('journal.show', $entry->journal_id) }}" class="text-sm font-mono text-primary-600 hover:text-primary-700">
                            {{ $entry->journal?->journal_number }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $entry->description ?: $entry->journal?->description }}</td>
                    <td class="px-4 py-3 text-sm text-right {{ $entry->debit > 0 ? 'text-gray-900 font-medium' : 'text-gray-400' }}">
                        {{ $entry->debit > 0 ? 'Rp ' . number_format($entry->debit, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-right {{ $entry->credit > 0 ? 'text-gray-900 font-medium' : 'text-gray-400' }}">
                        {{ $entry->credit > 0 ? 'Rp ' . number_format($entry->credit, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-right font-medium {{ $runningBalance >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                        Rp {{ number_format(abs($runningBalance), 0, ',', '.') }}
                        <span class="text-xs text-gray-400">{{ $runningBalance >= 0 ? 'D' : 'K' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm">Tidak ada transaksi pada periode ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($entries->count() > 0)
            <tfoot class="bg-gray-100">
                <tr class="font-semibold">
                    <td colspan="3" class="px-4 py-3 text-gray-700">Total</td>
                    <td class="px-4 py-3 text-right text-gray-900">Rp {{ number_format($entries->sum('debit'), 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right text-gray-900">Rp {{ number_format($entries->sum('credit'), 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right {{ $runningBalance >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                        Rp {{ number_format(abs($runningBalance), 0, ',', '.') }}
                        <span class="text-xs">{{ $runningBalance >= 0 ? 'D' : 'K' }}</span>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@else
<!-- No Account Selected -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12">
    <div class="flex flex-col items-center text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-1">Pilih Akun</h3>
        <p class="text-gray-500 text-sm">Silakan pilih akun terlebih dahulu untuk melihat buku besar</p>
    </div>
</div>
@endif
@endsection

@extends('layouts.app')

@section('title', 'Laporan Umur Piutang')
@section('subtitle', 'Aging Report Piutang')

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('receivable.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Piutang
        </a>
    </div>

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-gray-800">Laporan Umur Piutang</h3>
                    <p class="text-sm text-gray-500">Per Tanggal: {{ now()->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="p-4 bg-green-50 rounded-xl border border-green-200">
                    <p class="text-xs text-green-600 font-medium">Belum Jatuh Tempo</p>
                    <p class="text-xl font-bold text-green-700">{{ $aging['current']->count() }}</p>
                    <p class="text-sm text-green-600">Rp {{ number_format($aging['current']->sum('remaining_amount'), 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                    <p class="text-xs text-yellow-600 font-medium">1-30 Hari</p>
                    <p class="text-xl font-bold text-yellow-700">{{ $aging['1-30']->count() }}</p>
                    <p class="text-sm text-yellow-600">Rp {{ number_format($aging['1-30']->sum('remaining_amount'), 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-orange-50 rounded-xl border border-orange-200">
                    <p class="text-xs text-orange-600 font-medium">31-60 Hari</p>
                    <p class="text-xl font-bold text-orange-700">{{ $aging['31-60']->count() }}</p>
                    <p class="text-sm text-orange-600">Rp {{ number_format($aging['31-60']->sum('remaining_amount'), 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                    <p class="text-xs text-red-600 font-medium">61-90 Hari</p>
                    <p class="text-xl font-bold text-red-700">{{ $aging['61-90']->count() }}</p>
                    <p class="text-sm text-red-600">Rp {{ number_format($aging['61-90']->sum('remaining_amount'), 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-rose-50 rounded-xl border border-rose-200">
                    <p class="text-xs text-rose-600 font-medium">&gt; 90 Hari</p>
                    <p class="text-xl font-bold text-rose-700">{{ $aging['90+']->count() }}</p>
                    <p class="text-sm text-rose-600">Rp {{ number_format($aging['90+']->sum('remaining_amount'), 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Total Summary --}}
            @php
                $totalPiutang = $aging['current']->sum('remaining_amount') 
                              + $aging['1-30']->sum('remaining_amount') 
                              + $aging['31-60']->sum('remaining_amount') 
                              + $aging['61-90']->sum('remaining_amount') 
                              + $aging['90+']->sum('remaining_amount');
                $totalOverdue = $aging['1-30']->sum('remaining_amount') 
                              + $aging['31-60']->sum('remaining_amount') 
                              + $aging['61-90']->sum('remaining_amount') 
                              + $aging['90+']->sum('remaining_amount');
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-900 text-white rounded-xl">
                    <p class="text-sm text-gray-300">Total Piutang Belum Lunas</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-rose-600 text-white rounded-xl">
                    <p class="text-sm text-rose-200">Total Piutang Jatuh Tempo</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalOverdue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Aging Details --}}
    @foreach(['current' => ['Belum Jatuh Tempo', 'green'], '1-30' => ['Jatuh Tempo 1-30 Hari', 'yellow'], '31-60' => ['Jatuh Tempo 31-60 Hari', 'orange'], '61-90' => ['Jatuh Tempo 61-90 Hari', 'red'], '90+' => ['Jatuh Tempo > 90 Hari', 'rose']] as $key => $data)
    @if($aging[$key]->count() > 0)
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-{{ $data[1] }}-50">
            <div class="flex items-center justify-between">
                <h4 class="font-semibold text-{{ $data[1] }}-800">{{ $data[0] }}</h4>
                <span class="px-3 py-1 text-sm font-medium bg-{{ $data[1] }}-100 text-{{ $data[1] }}-700 rounded-lg">
                    {{ $aging[$key]->count() }} piutang
                </span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No. Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Hari</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Sisa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($aging[$key] as $receivable)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <a href="{{ route('receivable.show', $receivable) }}" class="font-mono text-sm text-primary-600 hover:text-primary-700">
                                {{ $receivable->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ $receivable->customer?->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $receivable->date?->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $receivable->due_date?->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 text-center">
                            @php
                                $days = now()->diffInDays($receivable->due_date, false);
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-lg {{ $days >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $days >= 0 ? $days . ' hari lagi' : abs($days) . ' hari' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-right text-gray-900">Rp {{ number_format($receivable->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-medium text-gray-900">Rp {{ number_format($receivable->remaining_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-{{ $data[1] }}-50">
                    <tr class="font-semibold">
                        <td colspan="5" class="px-6 py-3 text-{{ $data[1] }}-800">Subtotal</td>
                        <td class="px-6 py-3 text-right text-{{ $data[1] }}-800">Rp {{ number_format($aging[$key]->sum('amount'), 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right text-{{ $data[1] }}-800">Rp {{ number_format($aging[$key]->sum('remaining_amount'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
    @endforeach

    {{-- Empty State --}}
    @if($aging['current']->count() == 0 && $aging['1-30']->count() == 0 && $aging['31-60']->count() == 0 && $aging['61-90']->count() == 0 && $aging['90+']->count() == 0)
    <div class="bg-white rounded-2xl shadow-soft p-12">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Tidak Ada Piutang</h3>
            <p class="text-gray-500 text-sm">Semua piutang sudah lunas</p>
        </div>
    </div>
    @endif
</div>
@endsection

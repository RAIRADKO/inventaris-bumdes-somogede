@extends('layouts.app')

@section('title', 'Piutang')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Piutang</h1>
        <p class="text-gray-500 mt-1">Kelola piutang usaha</p>
    </div>
    <a href="{{ route('receivable.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Piutang
    </a>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Piutang Belum Dibayar</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalUnpaid ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Piutang Jatuh Tempo</p>
        <p class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalOverdue ?? 0, 0, ',', '.') }}</p>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">No. Invoice</th>
                    <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                    <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Jatuh Tempo</th>
                    <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                    <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Sisa</th>
                    <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($receivables ?? [] as $rcv)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $rcv->invoice_number }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $rcv->customer?->name }}</td>
                    <td class="py-4 px-6 text-sm {{ $rcv->due_date < now() && $rcv->status != 'paid' ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                        {{ $rcv->due_date->format('d/m/Y') }}
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $rcv->status == 'paid' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $rcv->status == 'partial' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $rcv->status == 'unpaid' ? 'bg-gray-100 text-gray-700' : '' }}
                            {{ $rcv->status == 'overdue' ? 'bg-red-100 text-red-700' : '' }}
                        ">{{ ucfirst($rcv->status) }}</span>
                    </td>
                    <td class="py-4 px-6 text-sm text-right text-gray-900">Rp {{ number_format($rcv->amount, 0, ',', '.') }}</td>
                    <td class="py-4 px-6 text-sm text-right font-semibold {{ $rcv->remaining_amount > 0 ? 'text-orange-600' : 'text-green-600' }}">
                        Rp {{ number_format($rcv->remaining_amount, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('receivable.show', $rcv) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">Lihat</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-500">Belum ada data piutang</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($receivables) && $receivables->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $receivables->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

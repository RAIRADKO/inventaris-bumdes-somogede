@extends('layouts.app')

@section('title', 'Detail Jurnal')
@section('subtitle', $journal->journal_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Back Button --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('journal.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Jurnal
        </a>

        @if($journal->status === 'draft')
        <div class="flex items-center space-x-2">
            <a href="{{ route('journal.edit', $journal) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-medium rounded-xl hover:bg-blue-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('journal.approve', $journal) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Setujui
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Journal Header --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-800">{{ $journal->journal_number }}</h3>
                        <p class="text-sm text-gray-500">{{ $journal->date->format('l, d F Y') }}</p>
                    </div>
                </div>
                @php
                    $statusColors = [
                        'draft' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                    ];
                    $statusLabels = [
                        'draft' => 'Draft',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ];
                @endphp
                <span class="px-4 py-2 text-sm font-semibold rounded-xl border {{ $statusColors[$journal->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$journal->status] ?? $journal->status }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Unit Usaha</label>
                    <p class="mt-1 text-gray-800 font-medium">{{ $journal->businessUnit?->name ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Periode Fiskal</label>
                    <p class="mt-1 text-gray-800 font-medium">{{ $journal->fiscalPeriod?->name ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Dibuat Oleh</label>
                    <p class="mt-1 text-gray-800 font-medium">{{ $journal->createdBy?->name ?? '-' }}</p>
                </div>
            </div>

            <div class="mb-6">
                <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Deskripsi</label>
                <p class="mt-1 text-gray-800">{{ $journal->description }}</p>
            </div>

            @if($journal->status === 'approved' && $journal->approvedBy)
            <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm text-emerald-700">
                        Disetujui oleh <strong>{{ $journal->approvedBy->name }}</strong> pada {{ $journal->approved_at?->format('d M Y H:i') }}
                    </span>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Journal Entries --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="font-semibold text-gray-800">Entri Jurnal</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Akun</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Kredit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($journal->entries as $entry)
                    <tr>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600">{{ $entry->account?->code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-800">{{ $entry->account?->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600 text-sm">{{ $entry->description ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($entry->debit > 0)
                                <span class="font-medium text-gray-800">Rp {{ number_format($entry->debit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($entry->credit > 0)
                                <span class="font-medium text-gray-800">Rp {{ number_format($entry->credit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-gray-700">Total</td>
                        <td class="px-6 py-4 text-right text-gray-800">Rp {{ number_format($journal->total_debit, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-gray-800">Rp {{ number_format($journal->total_credit, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

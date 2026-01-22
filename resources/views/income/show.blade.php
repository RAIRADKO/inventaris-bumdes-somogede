@extends('layouts.app')

@section('title', 'Detail Pemasukan')
@section('subtitle', $income->transaction_number)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('income.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-blue-200/50">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $income->transaction_number }}</h2>
                                <p class="text-sm text-gray-400">{{ $income->date->format('d F Y') }}</p>
                            </div>
                        </div>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'pending' => 'bg-amber-100 text-amber-700',
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'rejected' => 'bg-rose-100 text-rose-700',
                            ];
                            $statusLabels = [
                                'draft' => 'Draft',
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ];
                        @endphp
                        <span class="px-4 py-2 {{ $statusColors[$income->status] }} text-sm font-bold rounded-xl uppercase">
                            {{ $statusLabels[$income->status] }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Amount -->
                    <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Jumlah Pemasukan</p>
                        <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($income->amount, 0, ',', '.') }}</p>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Kategori</p>
                            <p class="font-semibold text-gray-800">{{ $income->category->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Unit Usaha</p>
                            <p class="font-semibold text-gray-800">{{ $income->businessUnit->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Sumber</p>
                            <p class="font-semibold text-gray-800">{{ $income->source ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">No. Referensi</p>
                            <p class="font-semibold text-gray-800">{{ $income->reference ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-sm text-gray-400 mb-2">Keterangan</p>
                        <p class="text-gray-700">{{ $income->description }}</p>
                    </div>

                    <!-- Attachment -->
                    @if($income->attachment)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-sm text-gray-400 mb-3">Lampiran</p>
                        <a href="{{ Storage::url($income->attachment) }}" target="_blank" 
                           class="inline-flex items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-xl transition group">
                            <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-700 group-hover:text-primary-600">Lihat Lampiran</p>
                                <p class="text-xs text-gray-400">Klik untuk membuka</p>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if($income->status === 'draft')
                    <a href="{{ route('income.edit', $income) }}" 
                       class="flex items-center justify-center w-full px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('income.submit', $income) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 btn-primary text-white font-semibold rounded-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Submit untuk Approval
                        </button>
                    </form>
                    <form action="{{ route('income.destroy', $income) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 border-2 border-rose-200 text-rose-600 font-semibold rounded-xl hover:bg-rose-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                    @endif

                    @if($income->status === 'pending' && auth()->user()->canApprove())
                    <form action="{{ route('income.approve', $income) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui
                        </button>
                    </form>
                    <form action="{{ route('income.reject', $income) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 border-2 border-rose-200 text-rose-600 font-semibold rounded-xl hover:bg-rose-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Informasi</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Dibuat oleh</p>
                        <p class="font-medium text-gray-700">{{ $income->createdBy->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Input</p>
                        <p class="font-medium text-gray-700">{{ $income->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($income->approved_at)
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Disetujui oleh</p>
                        <p class="font-medium text-gray-700">{{ $income->approvedBy->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Disetujui</p>
                        <p class="font-medium text-gray-700">{{ $income->approved_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('subtitle', $cash->transaction_number)

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('cash.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <!-- Detail Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
        <!-- Header -->
        <div class="p-6 {{ $cash->type == 'in' ? 'bg-gradient-to-br from-emerald-500 to-emerald-600' : 'bg-gradient-to-br from-rose-500 to-rose-600' }} text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
            <div class="relative">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="{{ $cash->type == 'in' ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium {{ $cash->type == 'in' ? 'text-emerald-100' : 'text-rose-100' }} mb-1">
                                {{ $cash->type == 'in' ? 'Kas Masuk' : 'Kas Keluar' }}
                            </p>
                            <p class="text-3xl font-bold">Rp {{ number_format($cash->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 bg-white/20 rounded-xl text-sm font-bold uppercase backdrop-blur-sm">
                        {{ $cash->status }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor Transaksi</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $cash->transaction_number }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $cash->date->format('d F Y') }}</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kategori</p>
                <p class="text-sm font-semibold text-gray-800">{{ $cash->category?->name ?? '-' }}</p>
            </div>
            
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan</p>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-700">{{ $cash->description }}</p>
                </div>
            </div>

            @if($cash->reference)
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">No. Referensi</p>
                <p class="text-sm font-semibold text-gray-800">{{ $cash->reference }}</p>
            </div>
            @endif

            @if($cash->attachment)
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Lampiran</p>
                <a href="{{ Storage::url($cash->attachment) }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2.5 bg-primary-50 text-primary-700 font-semibold rounded-xl hover:bg-primary-100 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Lihat Lampiran
                </a>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Dibuat oleh</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $cash->createdBy?->name ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $cash->created_at->format('d M Y, H:i') }}</p>
                </div>
                @if($cash->approved_by)
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Disetujui oleh</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $cash->approvedBy?->name ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $cash->approved_at?->format('d M Y, H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap gap-3 mt-6">
        @if($cash->status == 'draft')
        <a href="{{ route('cash.edit', $cash) }}" 
           class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <form action="{{ route('cash.submit', $cash) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/30">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Submit untuk Approval
            </button>
        </form>
        <form action="{{ route('cash.destroy', $cash) }}" method="POST" class="inline" 
              onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-rose-100 text-rose-700 font-semibold rounded-xl hover:bg-rose-200 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus
            </button>
        </form>
        @endif

        @can('approve')
        @if($cash->status == 'pending')
        <form action="{{ route('cash.approve', $cash) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/30">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Setujui
            </button>
        </form>
        <form action="{{ route('cash.reject', $cash) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-rose-600 text-white font-semibold rounded-xl hover:bg-rose-700 transition shadow-lg shadow-rose-600/30">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tolak
            </button>
        </form>
        @endif
        @endcan
    </div>
</div>
@endsection

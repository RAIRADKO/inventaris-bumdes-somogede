@extends('layouts.app')

@section('title', 'Tambah Hutang')
@section('subtitle', 'Catat hutang baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('payable.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-white">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-orange-200/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Data Hutang</h2>
                    <p class="text-sm text-gray-400 mt-0.5">Lengkapi informasi hutang</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('payable.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Supplier -->
                <div>
                    <label for="supplier_id" class="block text-sm font-bold text-gray-700 mb-2">Supplier *</label>
                    <select name="supplier_id" id="supplier_id" required
                            class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }} {{ $supplier->company ? '(' . $supplier->company . ')' : '' }}
                        </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Transaksi *</label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                               class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        @error('date')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-bold text-gray-700 mb-2">Jatuh Tempo *</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" required
                               class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        @error('due_date')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-bold text-gray-700 mb-2">Jumlah Hutang (Rp) *</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" step="1"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                           placeholder="0">
                    @error('amount')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Keterangan *</label>
                    <textarea name="description" id="description" rows="3" required
                              class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition resize-none"
                              placeholder="Jelaskan detail hutang...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attachment -->
                <div>
                    <label for="attachment" class="block text-sm font-bold text-gray-700 mb-2">Lampiran</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary-400 transition cursor-pointer group" 
                         onclick="document.getElementById('attachment').click()">
                        <input type="file" name="attachment" id="attachment" accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                        <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-primary-100 transition">
                            <svg class="w-7 h-7 text-gray-400 group-hover:text-primary-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">Klik untuk upload file</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF (max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('payable.index') }}" 
                   class="px-6 py-3 border-2 border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="btn-primary px-8 py-3 text-white font-semibold rounded-xl">
                    Simpan Hutang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

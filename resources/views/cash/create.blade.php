@extends('layouts.app')

@section('title', 'Tambah Transaksi Kas')
@section('subtitle', 'Catat transaksi kas baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('cash.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-xl font-bold text-gray-800">Data Transaksi</h2>
            <p class="text-sm text-gray-400 mt-1">Lengkapi informasi transaksi kas</p>
        </div>
        
        <form action="{{ route('cash.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Type Selection -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Tipe Transaksi *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex cursor-pointer group">
                            <input type="radio" name="type" value="in" {{ old('type') == 'in' ? 'checked' : '' }} required class="peer sr-only">
                            <div class="w-full p-5 border-2 rounded-2xl peer-checked:border-emerald-500 peer-checked:bg-gradient-to-br peer-checked:from-emerald-50 peer-checked:to-green-50 transition-all group-hover:border-gray-300">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-emerald-200/50">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">Kas Masuk</p>
                                        <p class="text-xs text-gray-400">Penerimaan kas</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer group">
                            <input type="radio" name="type" value="out" {{ old('type') == 'out' ? 'checked' : '' }} required class="peer sr-only">
                            <div class="w-full p-5 border-2 rounded-2xl peer-checked:border-rose-500 peer-checked:bg-gradient-to-br peer-checked:from-rose-50 peer-checked:to-red-50 transition-all group-hover:border-gray-300">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-rose-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-rose-200/50">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">Kas Keluar</p>
                                        <p class="text-xs text-gray-400">Pengeluaran kas</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('type')
                    <p class="mt-2 text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal *</label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                               class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        @error('date')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-bold text-gray-700 mb-2">Jumlah (Rp) *</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" step="1"
                               class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                               placeholder="0">
                        @error('amount')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" id="category_id" 
                            class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Keterangan *</label>
                    <textarea name="description" id="description" rows="3" required
                              class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition resize-none"
                              placeholder="Jelaskan detail transaksi...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reference -->
                <div>
                    <label for="reference" class="block text-sm font-bold text-gray-700 mb-2">No. Referensi</label>
                    <input type="text" name="reference" id="reference" value="{{ old('reference') }}"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                           placeholder="No. kwitansi, invoice, dll">
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
                <a href="{{ route('cash.index') }}" 
                   class="px-6 py-3 border-2 border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="btn-primary px-8 py-3 text-white font-semibold rounded-xl">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

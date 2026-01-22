@extends('layouts.app')

@section('title', 'Edit Pemasukan')
@section('subtitle', 'Ubah data transaksi pemasukan')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('income.show', $income) }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Detail</span>
    </a>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-blue-200/50">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Edit Pemasukan</h2>
                        <p class="text-sm text-gray-400 mt-0.5">{{ $income->transaction_number }}</p>
                    </div>
                </div>
                <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-lg uppercase">Draft</span>
            </div>
        </div>
        
        <form action="{{ route('income.update', $income) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal *</label>
                        <input type="date" name="date" id="date" value="{{ old('date', $income->date->format('Y-m-d')) }}" required
                               class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        @error('date')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-bold text-gray-700 mb-2">Jumlah (Rp) *</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $income->amount) }}" required min="0" step="1"
                               class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        @error('amount')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-bold text-gray-700 mb-2">Kategori *</label>
                    <select name="category_id" id="category_id" required
                            class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $income->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Business Unit -->
                <div>
                    <label for="business_unit_id" class="block text-sm font-bold text-gray-700 mb-2">Unit Usaha</label>
                    <select name="business_unit_id" id="business_unit_id"
                            class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        <option value="">Pilih Unit Usaha</option>
                        @foreach($businessUnits as $unit)
                        <option value="{{ $unit->id }}" {{ old('business_unit_id', $income->business_unit_id) == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Source -->
                <div>
                    <label for="source" class="block text-sm font-bold text-gray-700 mb-2">Sumber Pemasukan</label>
                    <input type="text" name="source" id="source" value="{{ old('source', $income->source) }}"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                           placeholder="Mis: Penjualan, Jasa, dll">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Keterangan *</label>
                    <textarea name="description" id="description" rows="3" required
                              class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition resize-none"
                              placeholder="Jelaskan detail transaksi...">{{ old('description', $income->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reference -->
                <div>
                    <label for="reference" class="block text-sm font-bold text-gray-700 mb-2">No. Referensi</label>
                    <input type="text" name="reference" id="reference" value="{{ old('reference', $income->reference) }}"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                           placeholder="No. kwitansi, invoice, dll">
                </div>

                <!-- Attachment -->
                <div>
                    <label for="attachment" class="block text-sm font-bold text-gray-700 mb-2">Lampiran</label>
                    @if($income->attachment)
                    <div class="mb-3 p-3 bg-gray-50 rounded-xl flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm text-gray-600">File lampiran tersedia</span>
                        </div>
                        <a href="{{ Storage::url($income->attachment) }}" target="_blank" class="text-primary-600 text-sm font-medium hover:underline">Lihat</a>
                    </div>
                    @endif
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary-400 transition cursor-pointer group" 
                         onclick="document.getElementById('attachment').click()">
                        <input type="file" name="attachment" id="attachment" accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                        <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-primary-100 transition">
                            <svg class="w-7 h-7 text-gray-400 group-hover:text-primary-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">{{ $income->attachment ? 'Ganti lampiran' : 'Klik untuk upload file' }}</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF (max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('income.show', $income) }}" 
                   class="px-6 py-3 border-2 border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="btn-primary px-8 py-3 text-white font-semibold rounded-xl">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

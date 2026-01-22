@extends('layouts.app')

@section('title', 'Tambah Aset')
@section('subtitle', 'Daftarkan aset baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('asset.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-white">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-violet-400 to-violet-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-violet-200/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Data Aset Baru</h2>
                    <p class="text-sm text-gray-400 mt-0.5">Lengkapi informasi aset</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Aset *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                           placeholder="Nama aset">
                    @error('name')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-bold text-gray-700 mb-2">Kategori *</label>
                        <select name="category_id" id="category_id" required
                                class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="{{ $unit->id }}" {{ old('business_unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Acquisition Date -->
                    <div>
                        <label for="acquisition_date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Perolehan *</label>
                        <input type="date" name="acquisition_date" id="acquisition_date" value="{{ old('acquisition_date', date('Y-m-d')) }}" required
                               class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        @error('acquisition_date')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Condition -->
                    <div>
                        <label for="condition" class="block text-sm font-bold text-gray-700 mb-2">Kondisi *</label>
                        <select name="condition" id="condition" required
                                class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                            <option value="">Pilih Kondisi</option>
                            <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                            <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Cukup Baik</option>
                            <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Kurang Baik</option>
                            <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Rusak</option>
                        </select>
                        @error('condition')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Acquisition Cost -->
                    <div>
                        <label for="acquisition_cost" class="block text-sm font-bold text-gray-700 mb-2">Harga Perolehan (Rp) *</label>
                        <input type="number" name="acquisition_cost" id="acquisition_cost" value="{{ old('acquisition_cost') }}" required min="0"
                               class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                               placeholder="0">
                        @error('acquisition_cost')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Salvage Value -->
                    <div>
                        <label for="salvage_value" class="block text-sm font-bold text-gray-700 mb-2">Nilai Residu (Rp)</label>
                        <input type="number" name="salvage_value" id="salvage_value" value="{{ old('salvage_value', 0) }}" min="0"
                               class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                               placeholder="0">
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-bold text-gray-700 mb-2">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                           placeholder="Lokasi penyimpanan aset">
                </div>

                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="block text-sm font-bold text-gray-700 mb-2">Nomor Seri</label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                           placeholder="Nomor seri atau kode unik aset">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition resize-none"
                              placeholder="Deskripsi tambahan tentang aset...">{{ old('description') }}</textarea>
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-bold text-gray-700 mb-2">Foto Aset</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary-400 transition cursor-pointer group" 
                         onclick="document.getElementById('photo').click()">
                        <input type="file" name="photo" id="photo" accept=".jpg,.jpeg,.png" class="hidden">
                        <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-primary-100 transition">
                            <svg class="w-7 h-7 text-gray-400 group-hover:text-primary-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">Klik untuk upload foto</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('asset.index') }}" 
                   class="px-6 py-3 border-2 border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="btn-primary px-8 py-3 text-white font-semibold rounded-xl">
                    Simpan Aset
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

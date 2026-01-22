@extends('layouts.app')

@section('title', 'Edit Aset')
@section('subtitle', 'Ubah data aset')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('asset.show', $asset) }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Detail</span>
    </a>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-white">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-violet-400 to-violet-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-violet-200/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Edit Aset</h2>
                    <p class="text-sm text-gray-400 mt-0.5">{{ $asset->code }} - {{ $asset->name }}</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('asset.update', $asset) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Aset *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $asset->name) }}" required
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
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
                            <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>
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
                            <option value="{{ $unit->id }}" {{ old('business_unit_id', $asset->business_unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Condition -->
                <div>
                    <label for="condition" class="block text-sm font-bold text-gray-700 mb-2">Kondisi *</label>
                    <select name="condition" id="condition" required
                            class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        <option value="good" {{ old('condition', $asset->condition) == 'good' ? 'selected' : '' }}>Baik</option>
                        <option value="fair" {{ old('condition', $asset->condition) == 'fair' ? 'selected' : '' }}>Cukup Baik</option>
                        <option value="poor" {{ old('condition', $asset->condition) == 'poor' ? 'selected' : '' }}>Kurang Baik</option>
                        <option value="damaged" {{ old('condition', $asset->condition) == 'damaged' ? 'selected' : '' }}>Rusak</option>
                    </select>
                    @error('condition')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-bold text-gray-700 mb-2">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $asset->location) }}"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                           placeholder="Lokasi penyimpanan aset">
                </div>

                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="block text-sm font-bold text-gray-700 mb-2">Nomor Seri</label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition resize-none">{{ old('description', $asset->description) }}</textarea>
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-bold text-gray-700 mb-2">Foto Aset</label>
                    @if($asset->photo)
                    <div class="mb-3 p-3 bg-gray-50 rounded-xl flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="{{ Storage::url($asset->photo) }}" alt="{{ $asset->name }}" class="w-16 h-16 object-cover rounded-lg mr-3">
                            <span class="text-sm text-gray-600">Foto tersedia</span>
                        </div>
                    </div>
                    @endif
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary-400 transition cursor-pointer group" 
                         onclick="document.getElementById('photo').click()">
                        <input type="file" name="photo" id="photo" accept=".jpg,.jpeg,.png" class="hidden">
                        <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-primary-100 transition">
                            <svg class="w-7 h-7 text-gray-400 group-hover:text-primary-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">{{ $asset->photo ? 'Ganti foto' : 'Klik untuk upload foto' }}</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('asset.show', $asset) }}" 
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

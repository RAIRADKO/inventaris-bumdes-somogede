@extends('layouts.app')

@section('title', 'Tambah Unit Usaha')
@section('subtitle', 'Buat unit usaha baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('business-unit.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-cyan-200/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Data Unit Usaha</h2>
                    <p class="text-sm text-gray-400 mt-0.5">Lengkapi informasi unit usaha</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('business-unit.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-bold text-gray-700 mb-2">Kode Unit *</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required maxlength="10"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition uppercase"
                           placeholder="Contoh: UNIT-001">
                    <p class="mt-1.5 text-xs text-gray-400">Maksimal 10 karakter, harus unik</p>
                    @error('code')
                    <p class="mt-1 text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Unit *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="200"
                           class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                           placeholder="Contoh: Toko Kelontong Desa">
                    @error('name')
                    <p class="mt-1 text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" maxlength="500"
                              class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition resize-none"
                              placeholder="Deskripsi singkat tentang unit usaha ini...">{{ old('description') }}</textarea>
                    <p class="mt-1.5 text-xs text-gray-400">Opsional, maksimal 500 karakter</p>
                    @error('description')
                    <p class="mt-1 text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('business-unit.index') }}" 
                   class="px-6 py-3 border-2 border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="btn-primary px-8 py-3 text-white font-semibold rounded-xl">
                    Simpan Unit Usaha
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Akun')
@section('subtitle', 'Ubah informasi akun')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('chart-of-account.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Akun
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-teal-50 to-emerald-50">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Edit Akun</h3>
                    <p class="text-sm text-gray-500">{{ $chartOfAccount->code }} - {{ $chartOfAccount->name }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('chart-of-account.update', $chartOfAccount) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Code --}}
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Akun <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code', $chartOfAccount->code) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition font-mono @error('code') border-rose-500 @enderror">
                    @error('code')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Akun <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $chartOfAccount->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('name') border-rose-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Akun <span class="text-rose-500">*</span>
                    </label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('type') border-rose-500 @enderror">
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $chartOfAccount->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Normal Balance --}}
                <div>
                    <label for="normal_balance" class="block text-sm font-medium text-gray-700 mb-2">
                        Saldo Normal <span class="text-rose-500">*</span>
                    </label>
                    <select name="normal_balance" id="normal_balance" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('normal_balance') border-rose-500 @enderror">
                        <option value="debit" {{ old('normal_balance', $chartOfAccount->normal_balance) == 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="credit" {{ old('normal_balance', $chartOfAccount->normal_balance) == 'credit' ? 'selected' : '' }}>Kredit</option>
                    </select>
                    @error('normal_balance')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Parent Account --}}
                <div class="md:col-span-2">
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Akun Induk
                    </label>
                    <select name="parent_id" id="parent_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="">Tidak ada (Akun Level 1)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $chartOfAccount->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->code }} - {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Is Header --}}
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_header" value="1" {{ old('is_header', $chartOfAccount->is_header) ? 'checked' : '' }}
                               class="w-5 h-5 text-primary-500 border-gray-300 rounded focus:ring-primary-500 transition">
                        <span class="ml-3 text-sm text-gray-700">
                            <span class="font-medium">Akun Header/Group</span>
                            <span class="text-gray-500 block text-xs">Centang jika akun ini adalah akun induk yang tidak digunakan untuk transaksi langsung</span>
                        </span>
                    </label>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition resize-none">{{ old('description', $chartOfAccount->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                <a href="{{ route('chart-of-account.index') }}" 
                   class="px-6 py-2.5 border border-gray-200 text-gray-600 font-medium rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="btn-primary px-6 py-2.5 text-white font-medium rounded-xl shadow-lg">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Kategori Aset')
@section('subtitle', 'Kelola kategori aset')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('asset.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Categories List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-white">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Kategori</h2>
                    <p class="text-sm text-gray-400 mt-1">{{ $categories->count() }} kategori terdaftar</p>
                </div>

                @if($categories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Umur (Thn)</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Tarif (%)</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Metode</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aset</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($categories as $category)
                            <tr class="table-row-hover">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">{{ $category->name }}</p>
                                    @if($category->description)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($category->description, 50) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-700 font-medium">{{ $category->useful_life_years }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-700 font-medium">{{ $category->depreciation_rate }}%</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 text-xs font-medium rounded-lg {{ $category->depreciation_method === 'straight_line' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ $category->depreciation_method === 'straight_line' ? 'Garis Lurus' : 'Saldo Menurun' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-lg text-sm">
                                        {{ $category->assets_count }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">Belum ada kategori aset</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Add Category Form -->
        <div>
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Tambah Kategori</h3>
                </div>
                <form action="{{ route('asset.categories.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori *</label>
                            <input type="text" name="name" id="name" required
                                   class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                                   placeholder="Mis: Kendaraan">
                            @error('name')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="useful_life_years" class="block text-sm font-bold text-gray-700 mb-2">Umur Manfaat (Tahun) *</label>
                            <input type="number" name="useful_life_years" id="useful_life_years" required min="1"
                                   class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                                   placeholder="5">
                            @error('useful_life_years')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="depreciation_rate" class="block text-sm font-bold text-gray-700 mb-2">Tarif Penyusutan (%) *</label>
                            <input type="number" name="depreciation_rate" id="depreciation_rate" required min="0" max="100" step="0.01"
                                   class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                                   placeholder="20">
                            @error('depreciation_rate')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="depreciation_method" class="block text-sm font-bold text-gray-700 mb-2">Metode Penyusutan *</label>
                            <select name="depreciation_method" id="depreciation_method" required
                                    class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                                <option value="straight_line">Garis Lurus</option>
                                <option value="declining_balance">Saldo Menurun</option>
                            </select>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" id="description" rows="2"
                                      class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition resize-none"
                                      placeholder="Deskripsi kategori..."></textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 btn-primary px-4 py-3 text-white font-semibold rounded-xl">
                        Tambah Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Unit Usaha')
@section('subtitle', $businessUnit->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('business-unit.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Unit Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-cyan-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-cyan-200/50">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $businessUnit->name }}</h2>
                                <p class="text-sm text-gray-400">Kode: {{ $businessUnit->code }}</p>
                            </div>
                        </div>
                        <span class="px-4 py-2 {{ $businessUnit->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }} text-sm font-bold rounded-xl uppercase">
                            {{ $businessUnit->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Financial Summary -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Total Pemasukan</p>
                            <p class="text-xl font-bold text-blue-600">Rp {{ number_format($income, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-rose-50 to-red-50 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Total Pengeluaran</p>
                            <p class="text-xl font-bold text-rose-600">Rp {{ number_format($expense, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Laba/Rugi</p>
                            <p class="text-xl font-bold {{ $profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                Rp {{ number_format(abs($profit), 0, ',', '.') }}
                                @if($profit < 0)
                                <span class="text-sm">(Rugi)</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($businessUnit->description)
                    <div class="mb-6">
                        <p class="text-sm text-gray-400 mb-2">Deskripsi</p>
                        <p class="text-gray-700">{{ $businessUnit->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Staff List -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Pengguna Terkait</h3>
                </div>
                @if($businessUnit->users->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($businessUnit->users as $user)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center mr-3">
                                <span class="text-white font-bold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                <p class="text-sm text-gray-400">{{ $user->role_label }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }} text-xs font-medium rounded-lg">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-8 text-center">
                    <p class="text-gray-500">Belum ada pengguna terkait</p>
                </div>
                @endif
            </div>

            <!-- Assets List -->
            @if($businessUnit->assets->count() > 0)
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Aset Unit</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Kondisi</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($businessUnit->assets->take(5) as $asset)
                            @php
                                $conditionColors = [
                                    'good' => 'bg-emerald-100 text-emerald-700',
                                    'fair' => 'bg-amber-100 text-amber-700',
                                    'poor' => 'bg-orange-100 text-orange-700',
                                    'damaged' => 'bg-rose-100 text-rose-700',
                                ];
                                $conditionLabels = [
                                    'good' => 'Baik',
                                    'fair' => 'Cukup',
                                    'poor' => 'Kurang',
                                    'damaged' => 'Rusak',
                                ];
                            @endphp
                            <tr class="table-row-hover">
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $asset->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $asset->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 {{ $conditionColors[$asset->condition] }} text-xs font-medium rounded-lg">
                                        {{ $conditionLabels[$asset->condition] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 text-right">Rp {{ number_format($asset->current_value, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($businessUnit->assets->count() > 5)
                <div class="p-4 border-t border-gray-100 text-center">
                    <a href="{{ route('asset.index', ['business_unit_id' => $businessUnit->id]) }}" class="text-primary-600 font-medium hover:underline">
                        Lihat semua aset →
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <a href="{{ route('business-unit.edit', $businessUnit) }}" 
                       class="flex items-center justify-center w-full px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    
                    <form action="{{ route('business-unit.toggle', $businessUnit) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 {{ $businessUnit->is_active ? 'border-2 border-amber-200 text-amber-600 hover:bg-amber-50' : 'btn-primary text-white' }} font-semibold rounded-xl transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $businessUnit->is_active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                            </svg>
                            {{ $businessUnit->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Statistik</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Pengguna</span>
                        <span class="font-bold text-gray-800">{{ $businessUnit->users->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Aset</span>
                        <span class="font-bold text-gray-800">{{ $businessUnit->assets->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

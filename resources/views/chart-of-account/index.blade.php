@extends('layouts.app')

@section('title', 'Daftar Akun')
@section('subtitle', 'Kelola bagan akun/chart of accounts')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-teal-400 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Bagan Akun</h3>
                <p class="text-sm text-gray-500">{{ $accounts->total() }} akun terdaftar</p>
            </div>
        </div>
        <a href="{{ route('chart-of-account.create') }}" 
           class="btn-primary inline-flex items-center px-5 py-2.5 text-white font-medium rounded-xl shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Akun
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Kode atau nama akun..."
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    <option value="">Semua Tipe</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-gray-800 text-white font-medium rounded-xl hover:bg-gray-700 transition">
                    Filter
                </button>
                <a href="{{ route('chart-of-account.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-600 font-medium rounded-xl hover:bg-gray-50 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Account Tree View --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Tree Structure --}}
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Struktur Akun
            </h4>
            <div class="space-y-1 text-sm max-h-[500px] overflow-y-auto">
                @foreach($accountTree as $account)
                    @include('chart-of-account.partials.tree-item', ['account' => $account, 'level' => 0])
                @endforeach
            </div>
        </div>

        {{-- Account List --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Akun</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($accounts as $account)
                        <tr class="table-row-hover transition">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-semibold text-gray-800">{{ $account->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($account->is_header)
                                        <span class="w-6 h-6 bg-amber-100 rounded-lg flex items-center justify-center mr-2">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                        </span>
                                    @endif
                                    <span class="{{ $account->is_header ? 'font-semibold' : '' }} text-gray-800" style="padding-left: {{ ($account->level - 1) * 12 }}px">
                                        {{ $account->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'asset' => 'bg-blue-100 text-blue-700',
                                        'liability' => 'bg-red-100 text-red-700',
                                        'equity' => 'bg-purple-100 text-purple-700',
                                        'revenue' => 'bg-green-100 text-green-700',
                                        'expense' => 'bg-orange-100 text-orange-700',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-lg {{ $typeColors[$account->type] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $types[$account->type] ?? $account->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($account->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-1">
                                    <a href="{{ route('chart-of-account.edit', $account) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('chart-of-account.toggle', $account) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" 
                                                title="{{ $account->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if($account->is_active)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form id="delete-form-coa-{{ $account->id }}" action="{{ route('chart-of-account.destroy', $account) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteModal('delete-form-coa-{{ $account->id }}', 'Yakin ingin menghapus akun {{ $account->name }}?')" 
                                                class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada akun</p>
                                    <p class="text-gray-400 text-sm mt-1">Tambahkan akun baru untuk memulai</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($accounts->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $accounts->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Anggaran')
@section('subtitle', 'Kelola perencanaan anggaran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Daftar Anggaran</h3>
                <p class="text-sm text-gray-500">{{ $budgets->total() }} anggaran tercatat</p>
            </div>
        </div>
        <a href="{{ route('budget.create') }}" 
           class="btn-primary inline-flex items-center px-5 py-2.5 text-white font-medium rounded-xl shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Anggaran
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nama anggaran..."
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Fiskal</label>
                <select name="fiscal_period_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    <option value="">Semua Periode</option>
                    @foreach($fiscalPeriods as $period)
                        <option value="{{ $period->id }}" {{ request('fiscal_period_id') == $period->id ? 'selected' : '' }}>
                            {{ $period->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Usaha</label>
                <select name="business_unit_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    <option value="">Semua Unit</option>
                    @foreach($businessUnits as $unit)
                        <option value="{{ $unit->id }}" {{ request('business_unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-gray-800 text-white font-medium rounded-xl hover:bg-gray-700 transition">
                    Filter
                </button>
                <a href="{{ route('budget.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-600 font-medium rounded-xl hover:bg-gray-50 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Budget List --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Anggaran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit Usaha</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Anggaran</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Realisasi</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($budgets as $budget)
                    <tr class="table-row-hover transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('budget.show', $budget) }}" class="font-medium text-gray-800 hover:text-primary-600">
                                {{ $budget->name }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $budget->items->count() }} item</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $budget->fiscalPeriod?->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $budget->businessUnit?->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-medium text-gray-800">Rp {{ number_format($budget->total_amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $percentage = $budget->realization_percentage;
                                $barColor = $percentage > 100 ? 'bg-rose-500' : ($percentage > 80 ? 'bg-amber-500' : 'bg-emerald-500');
                            @endphp
                            <div class="flex items-center justify-center">
                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden mr-2">
                                    <div class="{{ $barColor }} h-full rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-600">{{ $percentage }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-amber-100 text-amber-700',
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                ];
                                $statusLabels = [
                                    'draft' => 'Draft',
                                    'approved' => 'Disetujui',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$budget->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$budget->status] ?? $budget->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-1">
                                <a href="{{ route('budget.show', $budget) }}" 
                                   class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($budget->status === 'draft')
                                    <a href="{{ route('budget.edit', $budget) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('budget.approve', $budget) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('budget.destroy', $budget) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus anggaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada anggaran</p>
                                <p class="text-gray-400 text-sm mt-1">Buat anggaran baru untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($budgets->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $budgets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

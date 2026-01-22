@extends('layouts.app')

@section('title', 'Detail Anggaran')
@section('subtitle', $budget->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Back Button --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('budget.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Anggaran
        </a>

        @if($budget->status === 'draft')
        <div class="flex items-center space-x-2">
            <a href="{{ route('budget.edit', $budget) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-medium rounded-xl hover:bg-blue-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('budget.approve', $budget) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Setujui
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Budget Header --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-800">{{ $budget->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $budget->items->count() }} item anggaran</p>
                    </div>
                </div>
                @php
                    $statusColors = [
                        'draft' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    ];
                    $statusLabels = [
                        'draft' => 'Draft',
                        'approved' => 'Disetujui',
                    ];
                @endphp
                <span class="px-4 py-2 text-sm font-semibold rounded-xl border {{ $statusColors[$budget->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$budget->status] ?? $budget->status }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Periode Fiskal</label>
                    <p class="mt-1 text-gray-800 font-medium">{{ $budget->fiscalPeriod?->name ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Unit Usaha</label>
                    <p class="mt-1 text-gray-800 font-medium">{{ $budget->businessUnit?->name ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Dibuat Oleh</label>
                    <p class="mt-1 text-gray-800 font-medium">{{ $budget->createdBy?->name ?? '-' }}</p>
                </div>
            </div>

            @if($budget->description)
            <div class="mb-6">
                <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Deskripsi</label>
                <p class="mt-1 text-gray-800">{{ $budget->description }}</p>
            </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <p class="text-sm text-blue-600 font-medium">Total Anggaran</p>
                    <p class="text-2xl font-bold text-blue-800">Rp {{ number_format($budget->total_planned, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                    <p class="text-sm text-emerald-600 font-medium">Realisasi</p>
                    <p class="text-2xl font-bold text-emerald-800">Rp {{ number_format($budget->total_realized, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 {{ $budget->total_variance >= 0 ? 'bg-amber-50 border-amber-200' : 'bg-rose-50 border-rose-200' }} rounded-xl border">
                    <p class="text-sm {{ $budget->total_variance >= 0 ? 'text-amber-600' : 'text-rose-600' }} font-medium">Sisa Anggaran</p>
                    <p class="text-2xl font-bold {{ $budget->total_variance >= 0 ? 'text-amber-800' : 'text-rose-800' }}">
                        Rp {{ number_format(abs($budget->total_variance), 0, ',', '.') }}
                        @if($budget->total_variance < 0)
                            <span class="text-sm font-normal">(Over)</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($budget->status === 'approved' && $budget->approvedBy)
            <div class="mt-6 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm text-emerald-700">
                        Disetujui oleh <strong>{{ $budget->approvedBy->name }}</strong> pada {{ $budget->approved_at?->format('d M Y H:i') }}
                    </span>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Budget Items --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="font-semibold text-gray-800">Item Anggaran</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Akun</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Anggaran</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Realisasi</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($budget->items as $item)
                    @php
                        $percentage = $item->planned_amount > 0 ? round(($item->realized_amount / $item->planned_amount) * 100, 1) : 0;
                        $barColor = $percentage > 100 ? 'bg-rose-500' : ($percentage > 80 ? 'bg-amber-500' : 'bg-emerald-500');
                    @endphp
                    <tr>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600">{{ $item->account?->code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-800">{{ $item->account?->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600 text-sm">{{ $item->description ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-medium text-gray-800">Rp {{ number_format($item->planned_amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-medium text-gray-800">Rp {{ number_format($item->realized_amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center">
                                <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden mr-2">
                                    <div class="{{ $barColor }} h-full rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-600 w-10 text-right">{{ $percentage }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-gray-700">Total</td>
                        <td class="px-6 py-4 text-right text-gray-800">Rp {{ number_format($budget->total_planned, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-gray-800">Rp {{ number_format($budget->total_realized, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center text-gray-800">{{ $budget->realization_percentage }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

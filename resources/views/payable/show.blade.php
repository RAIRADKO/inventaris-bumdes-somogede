@extends('layouts.app')

@section('title', 'Detail Hutang')
@section('subtitle', $payable->invoice_number)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('payable.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payable Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-orange-200/50">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $payable->invoice_number }}</h2>
                                <p class="text-sm text-gray-400">{{ $payable->supplier->name ?? 'Supplier' }}</p>
                            </div>
                        </div>
                        @php
                            $statusColors = [
                                'unpaid' => 'bg-gray-100 text-gray-700',
                                'partial' => 'bg-amber-100 text-amber-700',
                                'paid' => 'bg-emerald-100 text-emerald-700',
                                'overdue' => 'bg-rose-100 text-rose-700',
                            ];
                            $statusLabels = [
                                'unpaid' => 'Belum Dibayar',
                                'partial' => 'Sebagian',
                                'paid' => 'Lunas',
                                'overdue' => 'Jatuh Tempo',
                            ];
                        @endphp
                        <span class="px-4 py-2 {{ $statusColors[$payable->status] }} text-sm font-bold rounded-xl uppercase">
                            {{ $statusLabels[$payable->status] }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Amounts -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="p-4 bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Total Hutang</p>
                            <p class="text-xl font-bold text-orange-600">Rp {{ number_format($payable->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Sudah Dibayar</p>
                            <p class="text-xl font-bold text-emerald-600">Rp {{ number_format($payable->paid_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-rose-50 to-red-50 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Sisa</p>
                            <p class="text-xl font-bold text-rose-600">Rp {{ number_format($payable->remaining_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Supplier</p>
                            <p class="font-semibold text-gray-800">{{ $payable->supplier->name ?? '-' }}</p>
                            @if($payable->supplier->company ?? false)
                            <p class="text-sm text-gray-500">{{ $payable->supplier->company }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Unit Usaha</p>
                            <p class="font-semibold text-gray-800">{{ $payable->businessUnit->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Tanggal Transaksi</p>
                            <p class="font-semibold text-gray-800">{{ $payable->date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Jatuh Tempo</p>
                            <p class="font-semibold {{ $payable->due_date < now() && $payable->status !== 'paid' ? 'text-rose-600' : 'text-gray-800' }}">
                                {{ $payable->due_date->format('d M Y') }}
                                @if(($payable->days_overdue ?? 0) > 0)
                                <span class="text-sm text-rose-500">({{ $payable->days_overdue }} hari lewat)</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-sm text-gray-400 mb-2">Keterangan</p>
                        <p class="text-gray-700">{{ $payable->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Riwayat Pembayaran</h3>
                </div>
                @if($payable->payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">No. Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Metode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($payable->payments as $payment)
                            <tr class="table-row-hover">
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $payment->payment_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->date->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-emerald-600 font-semibold text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->payment_method ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-8 text-center">
                    <p class="text-gray-500">Belum ada pembayaran</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Add Payment Card -->
            @if($payable->remaining_amount > 0)
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Tambah Pembayaran</h3>
                </div>
                <form action="{{ route('payable.payment', $payable) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal *</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                                   class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah (Rp) *</label>
                            <input type="number" name="amount" required min="1" max="{{ $payable->remaining_amount }}"
                                   class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                                   placeholder="Max: {{ number_format($payable->remaining_amount, 0, ',', '.') }}">
                            @error('amount')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Metode Pembayaran</label>
                            <select name="payment_method" class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition">
                                <option value="">Pilih</option>
                                <option value="cash">Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="check">Cek/Giro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Catatan</label>
                            <textarea name="notes" rows="2"
                                      class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition resize-none"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-4 btn-primary px-4 py-3 text-white font-semibold rounded-xl">
                        Simpan Pembayaran
                    </button>
                </form>
            </div>
            @endif

            <!-- Actions Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if($payable->paid_amount == 0)
                    <a href="{{ route('payable.edit', $payable) }}" 
                       class="flex items-center justify-center w-full px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form id="delete-form-payable-{{ $payable->id }}" action="{{ route('payable.destroy', $payable) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDeleteModal('delete-form-payable-{{ $payable->id }}', 'Yakin ingin menghapus hutang {{ $payable->invoice_number }}?')" 
                                class="flex items-center justify-center w-full px-4 py-3 border-2 border-rose-200 text-rose-600 font-semibold rounded-xl hover:bg-rose-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Informasi</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Dibuat oleh</p>
                        <p class="font-medium text-gray-700">{{ $payable->createdBy->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Dibuat</p>
                        <p class="font-medium text-gray-700">{{ $payable->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

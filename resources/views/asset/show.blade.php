@extends('layouts.app')

@section('title', 'Detail Aset')
@section('subtitle', $asset->code)

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
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Asset Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($asset->photo)
                            <img src="{{ Storage::url($asset->photo) }}" alt="{{ $asset->name }}" class="w-16 h-16 object-cover rounded-xl mr-4 shadow">
                            @else
                            <div class="w-16 h-16 bg-gradient-to-br from-violet-400 to-violet-600 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-violet-200/50">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            @endif
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $asset->name }}</h2>
                                <p class="text-sm text-gray-400">{{ $asset->code }}</p>
                            </div>
                        </div>
                        @php
                            $statusColors = [
                                'active' => 'bg-emerald-100 text-emerald-700',
                                'disposed' => 'bg-gray-100 text-gray-700',
                                'sold' => 'bg-blue-100 text-blue-700',
                                'lost' => 'bg-rose-100 text-rose-700',
                            ];
                            $statusLabels = [
                                'active' => 'Aktif',
                                'disposed' => 'Dihapuskan',
                                'sold' => 'Dijual',
                                'lost' => 'Hilang',
                            ];
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
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 {{ $conditionColors[$asset->condition] }} text-xs font-bold rounded-lg">
                                {{ $conditionLabels[$asset->condition] }}
                            </span>
                            <span class="px-3 py-1.5 {{ $statusColors[$asset->status] }} text-xs font-bold rounded-lg uppercase">
                                {{ $statusLabels[$asset->status] }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Values -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="p-4 bg-gradient-to-r from-violet-50 to-purple-50 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Harga Perolehan</p>
                            <p class="text-xl font-bold text-violet-600">Rp {{ number_format($asset->acquisition_cost, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Nilai Sekarang</p>
                            <p class="text-xl font-bold text-emerald-600">Rp {{ number_format($asset->current_value, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Kategori</p>
                            <p class="font-semibold text-gray-800">{{ $asset->category->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Unit Usaha</p>
                            <p class="font-semibold text-gray-800">{{ $asset->businessUnit->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Tanggal Perolehan</p>
                            <p class="font-semibold text-gray-800">{{ $asset->acquisition_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Lokasi</p>
                            <p class="font-semibold text-gray-800">{{ $asset->location ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Nomor Seri</p>
                            <p class="font-semibold text-gray-800">{{ $asset->serial_number ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Akumulasi Penyusutan</p>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($asset->accumulated_depreciation, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($asset->description)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-sm text-gray-400 mb-2">Keterangan</p>
                        <p class="text-gray-700">{{ $asset->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Depreciation History -->
            @if($asset->depreciations->count() > 0)
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Riwayat Penyusutan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nilai Penyusutan</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nilai Buku</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($asset->depreciations as $dep)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $dep->date->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 text-right">Rp {{ number_format($dep->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 text-right">Rp {{ number_format($dep->book_value, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if($asset->status === 'active')
                    <a href="{{ route('asset.edit', $asset) }}" 
                       class="flex items-center justify-center w-full px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    
                    <!-- Dispose Modal Trigger -->
                    <button type="button" onclick="document.getElementById('disposeModal').classList.remove('hidden')"
                            class="flex items-center justify-center w-full px-4 py-3 border-2 border-amber-200 text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Hapuskan Aset
                    </button>
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Informasi</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Didaftarkan oleh</p>
                        <p class="font-medium text-gray-700">{{ $asset->createdBy->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Didaftarkan</p>
                        <p class="font-medium text-gray-700">{{ $asset->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dispose Modal -->
<div id="disposeModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="document.getElementById('disposeModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Hapuskan Aset</h3>
            <form action="{{ route('asset.dispose', $asset) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status Penghapusan *</label>
                        <select name="status" required class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500">
                            <option value="disposed">Dihapuskan</option>
                            <option value="sold">Dijual</option>
                            <option value="lost">Hilang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal *</label>
                        <input type="date" name="disposal_date" required value="{{ date('Y-m-d') }}" class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nilai Penjualan (Rp)</label>
                        <input type="number" name="disposal_value" value="0" min="0" class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Catatan *</label>
                        <textarea name="disposal_notes" required rows="3" class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 resize-none" placeholder="Alasan penghapusan..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('disposeModal').classList.add('hidden')" class="px-4 py-2 border-2 border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Piutang')
@section('subtitle', $receivable->invoice_number)

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('receivable.show', $receivable) }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Edit Piutang</h3>
                    <p class="text-sm text-gray-500">{{ $receivable->invoice_number }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('receivable.update', $receivable) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Customer --}}
                <div class="md:col-span-2">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pelanggan <span class="text-rose-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $receivable->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->code }} - {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date --}}
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="date" id="date" value="{{ old('date', $receivable->date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    @error('date')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Due Date --}}
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Jatuh Tempo <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $receivable->due_date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    @error('due_date')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Amount --}}
                <div class="md:col-span-2">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Piutang <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $receivable->amount) }}" required
                               class="w-full pl-12 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                               min="0" step="1000">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="3" required
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition resize-none">{{ old('description', $receivable->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Attachment --}}
                <div class="md:col-span-2">
                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                        Lampiran
                    </label>
                    @if($receivable->attachment)
                        <p class="text-sm text-gray-500 mb-2">File saat ini: {{ basename($receivable->attachment) }}</p>
                    @endif
                    <input type="file" name="attachment" id="attachment"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                           accept=".jpg,.jpeg,.png,.pdf">
                    <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, PDF. Maks 2MB</p>
                    @error('attachment')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                <a href="{{ route('receivable.show', $receivable) }}" 
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

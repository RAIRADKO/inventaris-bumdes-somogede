@extends('layouts.app')

@section('title', 'Buat Anggaran')
@section('subtitle', 'Buat perencanaan anggaran baru')

@section('content')
<div class="max-w-4xl mx-auto" x-data="budgetForm()">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('budget.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Anggaran
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Buat Anggaran Baru</h3>
                    <p class="text-sm text-gray-500">Tentukan alokasi anggaran per akun</p>
                </div>
            </div>
        </div>

        <form action="{{ route('budget.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Anggaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('name') border-rose-500 @enderror"
                           placeholder="Contoh: Anggaran Operasional Q1 2026">
                    @error('name')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fiscal Period --}}
                <div>
                    <label for="fiscal_period_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Periode Fiskal <span class="text-rose-500">*</span>
                    </label>
                    <select name="fiscal_period_id" id="fiscal_period_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="">Pilih Periode</option>
                        @foreach($fiscalPeriods as $period)
                            <option value="{{ $period->id }}" {{ old('fiscal_period_id') == $period->id ? 'selected' : '' }}>
                                {{ $period->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Business Unit --}}
                <div>
                    <label for="business_unit_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Usaha <span class="text-rose-500">*</span>
                    </label>
                    <select name="business_unit_id" id="business_unit_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="">Pilih Unit Usaha</option>
                        @foreach($businessUnits as $unit)
                            <option value="{{ $unit->id }}" {{ old('business_unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="description" id="description" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition resize-none"
                              placeholder="Keterangan anggaran...">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Budget Items --}}
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-800">Item Anggaran</h4>
                    <button type="button" @click="addItem()" 
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Item
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase w-2/5">Akun</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase w-40">Jumlah Anggaran</th>
                                <th class="px-4 py-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-b border-gray-100">
                                    <td class="px-4 py-3">
                                        <select :name="'items[' + index + '][account_id]'" required x-model="item.account_id"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                                            <option value="">Pilih Akun</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" :name="'items[' + index + '][description]'" x-model="item.description"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                               placeholder="Keterangan...">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" :name="'items[' + index + '][planned_amount]'" x-model.number="item.planned_amount"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-right focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                               placeholder="0" min="0" step="1000" required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right font-semibold text-gray-700">Total Anggaran</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800" x-text="formatRupiah(totalAmount)"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                <a href="{{ route('budget.index') }}" 
                   class="px-6 py-2.5 border border-gray-200 text-gray-600 font-medium rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="btn-primary px-6 py-2.5 text-white font-medium rounded-xl shadow-lg">
                    Simpan Anggaran
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function budgetForm() {
    return {
        items: [
            { account_id: '', description: '', planned_amount: 0 },
        ],
        
        get totalAmount() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.planned_amount) || 0), 0);
        },
        
        addItem() {
            this.items.push({ account_id: '', description: '', planned_amount: 0 });
        },
        
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },
        
        formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }
    }
}
</script>
@endpush
@endsection

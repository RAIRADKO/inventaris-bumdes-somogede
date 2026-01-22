@extends('layouts.app')

@section('title', 'Edit Jurnal')
@section('subtitle', $journal->journal_number)

@section('content')
<div class="max-w-4xl mx-auto" x-data="journalForm()">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('journal.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Jurnal
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Edit Jurnal</h3>
                    <p class="text-sm text-gray-500">{{ $journal->journal_number }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('journal.update', $journal) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Date --}}
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="date" id="date" value="{{ old('date', $journal->date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                </div>

                {{-- Business Unit --}}
                <div>
                    <label for="business_unit_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Usaha <span class="text-rose-500">*</span>
                    </label>
                    <select name="business_unit_id" id="business_unit_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        @foreach($businessUnits as $unit)
                            <option value="{{ $unit->id }}" {{ old('business_unit_id', $journal->business_unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="2" required
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition resize-none">{{ old('description', $journal->description) }}</textarea>
                </div>
            </div>

            {{-- Journal Entries --}}
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-800">Entri Jurnal</h4>
                    <button type="button" @click="addEntry()" 
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Baris
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase w-2/5">Akun</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase w-32">Debit</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase w-32">Kredit</th>
                                <th class="px-4 py-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(entry, index) in entries" :key="index">
                                <tr class="border-b border-gray-100">
                                    <td class="px-4 py-3">
                                        <select :name="'entries[' + index + '][account_id]'" required x-model="entry.account_id"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                                            <option value="">Pilih Akun</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" :name="'entries[' + index + '][description]'" x-model="entry.description"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" :name="'entries[' + index + '][debit]'" x-model.number="entry.debit"
                                               @input="entry.credit = entry.debit > 0 ? 0 : entry.credit"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-right focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                               min="0" step="0.01">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" :name="'entries[' + index + '][credit]'" x-model.number="entry.credit"
                                               @input="entry.debit = entry.credit > 0 ? 0 : entry.debit"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-right focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                               min="0" step="0.01">
                                    </td>
                                    <td class="px-4 py-3">
                                        <button type="button" @click="removeEntry(index)" x-show="entries.length > 2"
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
                                <td colspan="2" class="px-4 py-3 text-right font-semibold text-gray-700">Total</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800" x-text="formatRupiah(totalDebit)"></td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800" x-text="formatRupiah(totalCredit)"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-right text-sm text-gray-500">Selisih</td>
                                <td colspan="2" class="px-4 py-2 text-center">
                                    <span :class="isBalanced ? 'text-emerald-600 bg-emerald-100' : 'text-rose-600 bg-rose-100'" 
                                          class="px-3 py-1 rounded-full text-sm font-medium"
                                          x-text="isBalanced ? 'Balance ✓' : formatRupiah(Math.abs(totalDebit - totalCredit))"></span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                <a href="{{ route('journal.index') }}" 
                   class="px-6 py-2.5 border border-gray-200 text-gray-600 font-medium rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" :disabled="!isBalanced"
                        :class="isBalanced ? 'btn-primary shadow-lg' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-6 py-2.5 text-white font-medium rounded-xl">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function journalForm() {
    return {
        entries: @json($journal->entries->map(fn($e) => [
            'account_id' => (string) $e->account_id,
            'description' => $e->description ?? '',
            'debit' => (float) $e->debit,
            'credit' => (float) $e->credit,
        ])),
        
        get totalDebit() {
            return this.entries.reduce((sum, e) => sum + (parseFloat(e.debit) || 0), 0);
        },
        
        get totalCredit() {
            return this.entries.reduce((sum, e) => sum + (parseFloat(e.credit) || 0), 0);
        },
        
        get isBalanced() {
            return Math.abs(this.totalDebit - this.totalCredit) < 0.01 && this.totalDebit > 0;
        },
        
        addEntry() {
            this.entries.push({ account_id: '', description: '', debit: 0, credit: 0 });
        },
        
        removeEntry(index) {
            if (this.entries.length > 2) {
                this.entries.splice(index, 1);
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

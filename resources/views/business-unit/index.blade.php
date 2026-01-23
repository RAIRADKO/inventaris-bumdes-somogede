@extends('layouts.app')

@section('title', 'Unit Usaha')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Unit Usaha</h1>
        <p class="text-gray-500 mt-1">Kelola unit usaha BUMDes</p>
    </div>
    <a href="{{ route('business-unit.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Unit
    </a>
</div>

<!-- Units Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($units ?? [] as $unit)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $unit->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ $unit->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        
        <h3 class="font-semibold text-gray-900 mb-1">{{ $unit->name }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ $unit->code }}</p>
        
        @if($unit->description)
        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $unit->description }}</p>
        @endif

        <div class="grid grid-cols-2 gap-4 text-center py-4 border-t border-gray-100">
            <div>
                <p class="text-xl font-bold text-gray-900">{{ $unit->users_count ?? 0 }}</p>
                <p class="text-xs text-gray-500">Pengguna</p>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-900">{{ $unit->assets_count ?? 0 }}</p>
                <p class="text-xs text-gray-500">Aset</p>
            </div>
        </div>

        <div class="flex gap-2 mt-4">
            <a href="{{ route('business-unit.show', $unit) }}" 
               class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                Detail
            </a>
            <a href="{{ route('business-unit.edit', $unit) }}" 
               class="px-4 py-2 border border-gray-300 text-gray-600 text-sm rounded-lg hover:bg-gray-50 transition">
                Edit
            </a>
            <form id="delete-form-unit-{{ $unit->id }}" action="{{ route('business-unit.destroy', $unit) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDeleteModal('delete-form-unit-{{ $unit->id }}', 'Yakin ingin menghapus unit usaha {{ $unit->name }}?')" 
                        class="px-3 py-2 text-rose-500 hover:bg-rose-50 text-sm rounded-lg transition" title="Hapus">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <p class="text-gray-500 mb-4">Belum ada unit usaha</p>
        <a href="{{ route('business-unit.create') }}" class="text-primary-600 hover:text-primary-700 font-medium">
            Tambah Unit Pertama
        </a>
    </div>
    @endforelse
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Pengguna')
@section('subtitle', $user->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('user.index') }}" class="inline-flex items-center text-gray-400 hover:text-gray-600 mb-6 group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium">Kembali ke Daftar</span>
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                <span class="text-white font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }} text-xs font-bold rounded-lg">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- User Info -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Role/Jabatan</p>
                            <p class="font-semibold text-gray-800">{{ $user->role_label }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Unit Usaha</p>
                            <p class="font-semibold text-gray-800">{{ $user->businessUnit->name ?? 'Pusat' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">No. Telepon</p>
                            <p class="font-semibold text-gray-800">{{ $user->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Terdaftar</p>
                            <p class="font-semibold text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-sm text-gray-400 mb-3">Hak Akses</p>
                        <div class="flex flex-wrap gap-2">
                            @if($user->canApprove())
                            <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg">Approval Transaksi</span>
                            @endif
                            @if($user->canEditTransactions())
                            <span class="px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg">Edit Transaksi</span>
                            @endif
                            @if($user->canViewAllData())
                            <span class="px-3 py-1.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-lg">Lihat Semua Data</span>
                            @endif
                            @if($user->canManageJournals())
                            <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-lg">Kelola Jurnal</span>
                            @endif
                            @if($user->canManageUsers())
                            <span class="px-3 py-1.5 bg-rose-100 text-rose-700 text-xs font-medium rounded-lg">Kelola Pengguna</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Logs -->
            @if($user->activityLogs->count() > 0)
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Aktivitas Terakhir</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($user->activityLogs as $log)
                    <div class="px-6 py-4 flex items-start">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700">{{ $log->description }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
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
                    <a href="{{ route('user.edit', $user) }}" 
                       class="flex items-center justify-center w-full px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    
                    <form action="{{ route('user.toggle', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 {{ $user->is_active ? 'border-2 border-amber-200 text-amber-600 hover:bg-amber-50' : 'btn-primary text-white' }} font-semibold rounded-xl transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $user->is_active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                            </svg>
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Reset Password Card -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Reset Password</h3>
                </div>
                <form action="{{ route('user.password', $user) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru *</label>
                            <input type="password" name="password" required
                                   class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                                   placeholder="Min. 8 karakter">
                            @error('password')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi *</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full border-2 border-gray-200 rounded-xl py-3 px-4 focus:ring-0 focus:border-primary-500 transition"
                                   placeholder="Ulangi password">
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-4 px-4 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

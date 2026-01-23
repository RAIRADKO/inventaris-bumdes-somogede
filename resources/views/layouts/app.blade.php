<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - BUMDes Somogede</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        dark: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'soft-lg': '0 10px 40px -10px rgba(0, 0, 0, 0.1), 0 2px 10px -2px rgba(0, 0, 0, 0.04)',
                        'glow': '0 0 20px rgba(16, 185, 129, 0.3)',
                        'glow-lg': '0 0 40px rgba(16, 185, 129, 0.4)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'slide-in-left': 'slideInLeft 0.3s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 3s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        /* Toast Notification Animations */
        @keyframes bounce-once {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.2); }
            50% { transform: scale(0.95); }
            75% { transform: scale(1.05); }
        }
        
        .animate-bounce-once {
            animation: bounce-once 0.6s ease-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
            20%, 40%, 60%, 80% { transform: translateX(2px); }
        }
        
        .animate-shake {
            animation: shake 0.5s ease-out;
        }
        
        @keyframes progress-bar {
            from { width: 100%; }
            to { width: 0%; }
        }
        
        .animate-progress-bar {
            animation: progress-bar 5s linear forwards;
        }
        
        .animate-progress-bar-slow {
            animation: progress-bar 8s linear forwards;
        }
        
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        
        .animate-bounce-slow {
            animation: bounce-slow 1.5s ease-in-out infinite;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Sidebar styles */
        .sidebar-link {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            transform: scaleY(1);
        }
        
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.1) 0%, transparent 100%);
            color: #059669;
            font-weight: 600;
        }
        
        .sidebar-link:hover {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.05) 0%, transparent 100%);
        }
        
        /* Glass effect */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Card hover effect */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
        }
        
        /* Button styles */
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }
        
        /* Input focus */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        /* Table row hover */
        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.03) 0%, transparent 100%);
        }
        
        /* Status badges animation */
        .badge-pulse {
            animation: pulse 2s infinite;
        }
        
        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-white to-emerald-50/30 font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-full">
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" x-cloak
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-dark-900/60 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-white/80 backdrop-blur-xl border-r border-gray-100/50 transform transition-all duration-300 ease-out lg:translate-x-0 shadow-soft-lg flex flex-col overflow-hidden">
            
            <!-- Logo -->
            <div class="flex items-center justify-between h-20 px-6 border-b border-gray-100/50">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center shadow-glow animate-float">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white animate-pulse"></div>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl gradient-text">BUMDes</h1>
                        <p class="text-xs text-gray-400 font-medium tracking-wide">SOMOGEDE</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-bold text-gray-300 uppercase tracking-[0.2em]">Transaksi</p>
                </div>
                
                <a href="{{ route('cash.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('cash.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-100 to-green-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Kas</span>
                </a>

                <a href="{{ route('income.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('income.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Pemasukan</span>
                </a>

                <a href="{{ route('expense.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('expense.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-rose-100 to-rose-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Pengeluaran</span>
                </a>

                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-bold text-gray-300 uppercase tracking-[0.2em]">Piutang & Hutang</p>
                </div>

                <a href="{{ route('receivable.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('receivable.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Piutang</span>
                </a>

                <a href="{{ route('payable.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('payable.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-orange-100 to-orange-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="font-medium">Hutang</span>
                </a>

                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-bold text-gray-300 uppercase tracking-[0.2em]">Aset & Unit</p>
                </div>

                <a href="{{ route('asset.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('asset.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-violet-100 to-violet-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="font-medium">Aset</span>
                </a>

                <a href="{{ route('business-unit.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('business-unit.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="font-medium">Unit Usaha</span>
                </a>

                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-bold text-gray-300 uppercase tracking-[0.2em]">Akuntansi</p>
                </div>

                <a href="{{ route('chart-of-account.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('chart-of-account.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-teal-100 to-teal-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Daftar Akun</span>
                </a>

                <a href="{{ route('journal.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('journal.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="font-medium">Jurnal Umum</span>
                </a>

                <a href="{{ route('budget.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('budget.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Anggaran</span>
                </a>

                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-bold text-gray-300 uppercase tracking-[0.2em]">Laporan</p>
                </div>

                <a href="{{ route('report.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('report.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Laporan</span>
                </a>

                @can('manageUsers')
                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-bold text-gray-300 uppercase tracking-[0.2em]">Pengaturan</p>
                </div>

                <a href="{{ route('user.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3.5 text-gray-600 rounded-xl {{ request()->routeIs('user.*') ? 'active' : '' }}">
                    <div class="w-9 h-9 bg-gradient-to-br from-slate-100 to-slate-50 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Pengguna</span>
                </a>
                @endcan
            </nav>

            <!-- User section -->
            <div class="p-4 border-t border-gray-100/50 bg-gradient-to-r from-gray-50/50 to-transparent">
                <div class="flex items-center p-3 rounded-2xl bg-white shadow-soft">
                    <div class="relative">
                        <div class="w-11 h-11 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-sm">
                            <span class="text-white font-bold text-lg">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-400 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400 font-medium">{{ auth()->user()->role_label ?? 'Direktur' }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2.5 text-gray-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all duration-200" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="lg:ml-72">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 glass border-b border-gray-100/50">
                <div class="flex items-center justify-between h-20 px-6 lg:px-8">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                            <p class="text-sm text-gray-400 mt-0.5">@yield('subtitle', 'Selamat datang kembali!')</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="hidden md:flex items-center px-4 py-2.5 bg-white rounded-xl shadow-soft border border-gray-100/50">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-600">{{ now()->format('l, d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="p-6 lg:p-8 animate-fade-in">
                <!-- Toast Notifications Container -->
                @if(session('success') || session('error') || session('warning') || session('info'))
                <div x-data="toastNotification()" x-init="init()" class="fixed top-6 right-6 z-50 flex flex-col space-y-4">
                    @if(session('success'))
                    <div x-show="showSuccess" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
                         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
                         x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
                         class="min-w-[360px] max-w-md bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-emerald-100/50 overflow-hidden"
                         style="box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.25);">
                        
                        <!-- Success Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-400/10 via-green-400/10 to-emerald-400/10 animate-pulse"></div>
                        
                        <div class="relative p-5">
                            <div class="flex items-start">
                                <!-- Animated Icon -->
                                <div class="flex-shrink-0 relative">
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-green-500 rounded-2xl flex items-center justify-center shadow-lg transform transition-transform duration-300 hover:scale-110">
                                        <svg class="w-6 h-6 text-white animate-bounce-once" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <!-- Sparkles -->
                                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-yellow-400 rounded-full animate-ping"></div>
                                    <div class="absolute -bottom-1 -left-1 w-2 h-2 bg-emerald-400 rounded-full animate-ping" style="animation-delay: 0.5s;"></div>
                                </div>
                                
                                <!-- Content -->
                                <div class="ml-4 flex-1 min-w-0">
                                    <h4 class="text-base font-bold text-emerald-800 flex items-center">
                                        <span>Berhasil!</span>
                                        <span class="ml-2 text-lg">🎉</span>
                                    </h4>
                                    <p class="mt-1 text-sm text-emerald-600 leading-relaxed">{{ session('success') }}</p>
                                </div>
                                
                                <!-- Close Button -->
                                <button @click="showSuccess = false" 
                                        class="flex-shrink-0 ml-3 p-2 text-emerald-400 hover:text-emerald-600 hover:bg-emerald-100 rounded-xl transition-all duration-200 hover:rotate-90">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="h-1 bg-emerald-100">
                            <div class="h-full bg-gradient-to-r from-emerald-400 via-green-500 to-emerald-400 rounded-full animate-progress-bar"></div>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div x-show="showError" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
                         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
                         x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
                         class="min-w-[360px] max-w-md bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-rose-100/50 overflow-hidden"
                         style="box-shadow: 0 25px 50px -12px rgba(244, 63, 94, 0.25);">
                        
                        <!-- Error Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-rose-400/10 via-red-400/10 to-rose-400/10 animate-pulse"></div>
                        
                        <div class="relative p-5">
                            <div class="flex items-start">
                                <!-- Animated Icon -->
                                <div class="flex-shrink-0 relative">
                                    <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg transform transition-transform duration-300 hover:scale-110 animate-shake">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <!-- Warning Ripple -->
                                    <div class="absolute inset-0 rounded-2xl bg-rose-400 animate-ping opacity-20"></div>
                                </div>
                                
                                <!-- Content -->
                                <div class="ml-4 flex-1 min-w-0">
                                    <h4 class="text-base font-bold text-rose-800 flex items-center">
                                        <span>Gagal!</span>
                                        <span class="ml-2 text-lg">⚠️</span>
                                    </h4>
                                    <p class="mt-1 text-sm text-rose-600 leading-relaxed">{{ session('error') }}</p>
                                </div>
                                
                                <!-- Close Button -->
                                <button @click="showError = false" 
                                        class="flex-shrink-0 ml-3 p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-100 rounded-xl transition-all duration-200 hover:rotate-90">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="h-1 bg-rose-100">
                            <div class="h-full bg-gradient-to-r from-rose-400 via-red-500 to-rose-400 rounded-full animate-progress-bar-slow"></div>
                        </div>
                    </div>
                    @endif

                    @if(session('warning'))
                    <div x-show="showWarning" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
                         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
                         x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
                         class="min-w-[360px] max-w-md bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-amber-100/50 overflow-hidden"
                         style="box-shadow: 0 25px 50px -12px rgba(245, 158, 11, 0.25);">
                        
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-400/10 via-yellow-400/10 to-amber-400/10 animate-pulse"></div>
                        
                        <div class="relative p-5">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 relative">
                                    <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-2xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="ml-4 flex-1 min-w-0">
                                    <h4 class="text-base font-bold text-amber-800 flex items-center">
                                        <span>Peringatan</span>
                                        <span class="ml-2 text-lg">⚡</span>
                                    </h4>
                                    <p class="mt-1 text-sm text-amber-600 leading-relaxed">{{ session('warning') }}</p>
                                </div>
                                
                                <button @click="showWarning = false" 
                                        class="flex-shrink-0 ml-3 p-2 text-amber-400 hover:text-amber-600 hover:bg-amber-100 rounded-xl transition-all duration-200 hover:rotate-90">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="h-1 bg-amber-100">
                            <div class="h-full bg-gradient-to-r from-amber-400 via-yellow-500 to-amber-400 rounded-full animate-progress-bar"></div>
                        </div>
                    </div>
                    @endif

                    @if(session('info'))
                    <div x-show="showInfo" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
                         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
                         x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
                         class="min-w-[360px] max-w-md bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-blue-100/50 overflow-hidden"
                         style="box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.25);">
                        
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-400/10 via-indigo-400/10 to-blue-400/10 animate-pulse"></div>
                        
                        <div class="relative p-5">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 relative">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="ml-4 flex-1 min-w-0">
                                    <h4 class="text-base font-bold text-blue-800 flex items-center">
                                        <span>Informasi</span>
                                        <span class="ml-2 text-lg">💡</span>
                                    </h4>
                                    <p class="mt-1 text-sm text-blue-600 leading-relaxed">{{ session('info') }}</p>
                                </div>
                                
                                <button @click="showInfo = false" 
                                        class="flex-shrink-0 ml-3 p-2 text-blue-400 hover:text-blue-600 hover:bg-blue-100 rounded-xl transition-all duration-200 hover:rotate-90">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="h-1 bg-blue-100">
                            <div class="h-full bg-gradient-to-r from-blue-400 via-indigo-500 to-blue-400 rounded-full animate-progress-bar"></div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="px-6 lg:px-8 py-6 border-t border-gray-100/50">
                <p class="text-center text-sm text-gray-400">
                    &copy; {{ date('Y') }} BUMDes Somogede. Dibuat dengan ❤️ untuk kemajuan desa.
                </p>
            </footer>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div x-data="deleteConfirmation()" x-cloak>
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] overflow-y-auto"
             @keydown.escape.window="close()">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm" @click="close()"></div>
            
            <!-- Modal -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="isOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                     class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden"
                     @click.stop>
                    
                    <!-- Decorative Top Bar -->
                    <div class="h-2 bg-gradient-to-r from-rose-400 via-red-500 to-rose-400"></div>
                    
                    <!-- Content -->
                    <div class="p-8 text-center">
                        <!-- Animated Icon -->
                        <div class="mx-auto mb-6 relative">
                            <div class="w-20 h-20 bg-gradient-to-br from-rose-100 to-red-50 rounded-full flex items-center justify-center mx-auto animate-pulse">
                                <div class="w-16 h-16 bg-gradient-to-br from-rose-400 to-red-500 rounded-full flex items-center justify-center shadow-lg animate-bounce-slow">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- Warning Ripple -->
                            <div class="absolute inset-0 rounded-full bg-rose-400/20 animate-ping"></div>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">
                            Konfirmasi Hapus
                        </h3>
                        
                        <!-- Message -->
                        <p class="text-gray-500 mb-2" x-text="message"></p>
                        <p class="text-sm text-rose-500 font-medium mb-8">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Tindakan ini tidak dapat dibatalkan!
                        </p>
                        
                        <!-- Buttons -->
                        <div class="flex items-center justify-center space-x-4">
                            <button @click="close()" 
                                    class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-2xl hover:bg-gray-200 transition-all duration-200 hover:scale-105">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Batal
                                </span>
                            </button>
                            <button @click="confirmDelete()" 
                                    class="px-8 py-3 bg-gradient-to-r from-rose-500 to-red-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105 hover:from-rose-600 hover:to-red-700">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Ya, Hapus!
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Delete Confirmation Modal Component
        function deleteConfirmation() {
            return {
                isOpen: false,
                message: '',
                formId: null,
                init() {
                    // Listen for custom event to open modal
                    document.addEventListener('open-delete-modal', (e) => {
                        this.open(e.detail.formId, e.detail.message);
                    });
                },
                open(formId, message = 'Apakah Anda yakin ingin menghapus data ini?') {
                    this.formId = formId;
                    this.message = message;
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                close() {
                    this.isOpen = false;
                    document.body.style.overflow = '';
                },
                confirmDelete() {
                    if (this.formId) {
                        document.getElementById(this.formId).submit();
                    }
                    this.close();
                }
            }
        }
        
        // Global function to trigger delete modal from anywhere
        function confirmDeleteModal(formId, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            document.dispatchEvent(new CustomEvent('open-delete-modal', { 
                detail: { formId, message } 
            }));
            return false; // Prevent form submission
        }

        // Toast Notification Component
        function toastNotification() {
            return {
                showSuccess: true,
                showError: true,
                showWarning: true,
                showInfo: true,
                init() {
                    // Auto-dismiss success after 5 seconds
                    if (this.showSuccess) {
                        setTimeout(() => { this.showSuccess = false; }, 5000);
                    }
                    // Auto-dismiss error after 8 seconds (longer for errors)
                    if (this.showError) {
                        setTimeout(() => { this.showError = false; }, 8000);
                    }
                    // Auto-dismiss warning after 5 seconds
                    if (this.showWarning) {
                        setTimeout(() => { this.showWarning = false; }, 5000);
                    }
                    // Auto-dismiss info after 5 seconds
                    if (this.showInfo) {
                        setTimeout(() => { this.showInfo = false; }, 5000);
                    }
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>

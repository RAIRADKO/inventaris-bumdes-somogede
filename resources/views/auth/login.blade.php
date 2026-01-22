<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BUMDes Somogede</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0',
                            300: '#6ee7b7', 400: '#34d399', 500: '#10b981', 
                            600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Keyframe Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(3deg); }
        }
        @keyframes float-reverse {
            0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
            50% { transform: translateY(20px) rotate(-3deg) scale(1.05); }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.6; }
            100% { transform: scale(0.95); opacity: 1; }
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slide-right {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes rotate-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        @keyframes bounce-soft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        
        /* Animation Classes */
        .animate-float { animation: float 8s ease-in-out infinite; }
        .animate-float-reverse { animation: float-reverse 7s ease-in-out infinite; }
        .animate-float-delay-1 { animation: float 8s ease-in-out infinite 1s; }
        .animate-float-delay-2 { animation: float 8s ease-in-out infinite 2s; }
        .animate-pulse-ring { animation: pulse-ring 3s ease-in-out infinite; }
        .animate-slide-up { animation: slide-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-slide-up-delay { animation: slide-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards; opacity: 0; }
        .animate-slide-right { animation: slide-right 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-rotate-slow { animation: rotate-slow 30s linear infinite; }
        .animate-gradient { animation: gradient-shift 4s ease infinite; background-size: 200% 200%; }
        .animate-bounce-soft { animation: bounce-soft 2s ease-in-out infinite; }
        .animate-fade-in { animation: fade-in 1s ease forwards; }
        
        /* Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }
        .glass-dark {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        
        /* Input Focus Effects */
        .input-glow:focus {
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1), 0 0 20px rgba(16, 185, 129, 0.2);
        }
        
        /* Button Shimmer */
        .btn-shimmer {
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255,255,255,0.4), 
                transparent
            );
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #10b981, #34d399, #6ee7b7, #10b981);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-shift 4s ease infinite;
        }
        
        /* Feature Card Hover */
        .feature-card {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Decorative Elements */
        .deco-circle {
            border-radius: 50%;
            filter: blur(60px);
        }
        
        /* Mesh Gradient Background */
        .mesh-bg {
            background: 
                radial-gradient(ellipse at 20% 20%, rgba(16, 185, 129, 0.3) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(52, 211, 153, 0.3) 0%, transparent 50%),
                radial-gradient(ellipse at 60% 10%, rgba(6, 78, 59, 0.4) 0%, transparent 40%),
                radial-gradient(ellipse at 40% 90%, rgba(110, 231, 183, 0.2) 0%, transparent 40%),
                linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
        }
    </style>
</head>
<body class="h-full font-sans antialiased overflow-x-hidden">
    <!-- Main Container with Split Layout -->
    <div class="min-h-full flex">
        
        <!-- Left Side - Branding & Info (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <!-- Mesh Gradient Background -->
            <div class="absolute inset-0 mesh-bg"></div>
            
            <!-- Animated Decorative Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <!-- Large floating circles -->
                <div class="absolute -top-32 -left-32 w-96 h-96 deco-circle bg-primary-400/30 animate-float"></div>
                <div class="absolute top-1/4 right-0 w-80 h-80 deco-circle bg-emerald-300/20 animate-float-reverse"></div>
                <div class="absolute bottom-20 left-1/4 w-72 h-72 deco-circle bg-teal-400/25 animate-float-delay-1"></div>
                <div class="absolute -bottom-20 -right-20 w-96 h-96 deco-circle bg-primary-300/30 animate-float-delay-2"></div>
                
                <!-- Rotating ring -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-white/10 rounded-full animate-rotate-slow"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] border border-white/5 rounded-full animate-rotate-slow" style="animation-direction: reverse; animation-duration: 25s;"></div>
                
                <!-- Grid Pattern -->
                <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M0 0h1v40H0V0zm39 0h1v40h-1V0zM0 0v1h40V0H0zm0 39v1h40v-1H0z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center px-12 xl:px-20 py-12">
                <!-- Logo & Title -->
                <div class="animate-slide-right">
                    <div class="flex items-center mb-12">
                        <div class="relative">
                            <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-2xl shadow-black/20">
                                <svg class="w-12 h-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-3 border-white animate-bounce-soft"></div>
                        </div>
                        <div class="ml-5">
                            <h1 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight">BUMDes</h1>
                            <p class="text-primary-200 text-lg font-medium">Somogede</p>
                        </div>
                    </div>
                    
                    <!-- Main Headline -->
                    <h2 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight mb-6">
                        Sistem Akuntansi<br>
                        <span class="gradient-text">Keuangan Modern</span>
                    </h2>
                    
                    <p class="text-primary-100/90 text-lg xl:text-xl mb-12 max-w-lg leading-relaxed">
                        Kelola keuangan desa dengan sistem terintegrasi, transparan, dan mudah digunakan untuk kemajuan bersama.
                    </p>
                </div>
                
                <!-- Feature Cards -->
                <div class="grid gap-4 animate-slide-up-delay" style="animation-delay: 0.3s;">
                    <div class="feature-card glass-dark rounded-2xl p-5 border border-white/10">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-400 to-emerald-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-white font-bold text-lg">Laporan Real-time</h3>
                                <p class="text-primary-200/80 text-sm">Dashboard interaktif dengan data terkini</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="feature-card glass-dark rounded-2xl p-5 border border-white/10">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-white font-bold text-lg">Keamanan Terjamin</h3>
                                <p class="text-primary-200/80 text-sm">Data terenkripsi dan backup otomatis</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="feature-card glass-dark rounded-2xl p-5 border border-white/10">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-white font-bold text-lg">Multi-Pengguna</h3>
                                <p class="text-primary-200/80 text-sm">Kelola akses sesuai peran masing-masing</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center relative">
            <!-- Background for Mobile -->
            <div class="absolute inset-0 lg:hidden mesh-bg">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-64 h-64 deco-circle bg-primary-400/30 animate-float"></div>
                    <div class="absolute bottom-10 -left-10 w-48 h-48 deco-circle bg-emerald-300/20 animate-float-reverse"></div>
                </div>
            </div>
            
            <!-- White/Light Background for Desktop -->
            <div class="hidden lg:block absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100"></div>
            
            <!-- Decorative Shape on Desktop -->
            <div class="hidden lg:block absolute top-0 left-0 w-32 h-32 bg-primary-500/10 rounded-br-[100px]"></div>
            <div class="hidden lg:block absolute bottom-0 right-0 w-48 h-48 bg-primary-500/5 rounded-tl-[120px]"></div>
            
            <!-- Login Card -->
            <div class="relative z-10 w-full max-w-xl mx-auto px-6 py-12 lg:px-16">
                <div class="lg:hidden text-center mb-10 animate-slide-up">
                    <div class="inline-block">
                        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-2xl mx-auto mb-4">
                            <svg class="w-12 h-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-extrabold text-white">BUMDes Somogede</h1>
                        <p class="text-primary-200 text-sm">Sistem Akuntansi Keuangan</p>
                    </div>
                </div>
                
                <div class="glass rounded-3xl shadow-2xl shadow-black/5 p-8 lg:p-10 border border-white/50 lg:border-gray-100 animate-slide-up lg:bg-white lg:backdrop-blur-none">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="hidden lg:flex w-16 h-16 bg-gradient-to-br from-primary-500 to-emerald-500 rounded-2xl items-center justify-center mx-auto mb-5 shadow-lg shadow-primary-500/30 animate-pulse-ring">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-extrabold text-gray-800 mb-2">Selamat Datang!</h2>
                        <p class="text-gray-500">Masuk ke akun Anda untuk melanjutkan</p>
                    </div>
                    
                    <!-- Error Alert -->
                    @if($errors->any())
                    <div class="mb-6 p-4 bg-gradient-to-r from-rose-50 to-red-50 border border-rose-200 rounded-2xl animate-fade-in">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-rose-700">{{ $errors->first() }}</span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Login Form -->
                    <form action="{{ route('login') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                                       class="input-glow w-full pl-12 pr-4 py-4 bg-gray-50/80 border-2 border-gray-100 rounded-2xl focus:bg-white focus:ring-0 focus:border-primary-500 transition-all duration-300 text-gray-800 placeholder-gray-400"
                                       placeholder="nama@email.com">
                            </div>
                        </div>
                        
                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password" required autocomplete="current-password"
                                       class="input-glow w-full pl-12 pr-12 py-4 bg-gray-50/80 border-2 border-gray-100 rounded-2xl focus:bg-white focus:ring-0 focus:border-primary-500 transition-all duration-300 text-gray-800 placeholder-gray-400"
                                       placeholder="••••••••">
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center z-10">
                                    <svg id="eye-open" class="w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eye-closed" class="w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="remember" class="peer sr-only">
                                    <div class="w-5 h-5 bg-gray-100 border-2 border-gray-200 rounded-md peer-checked:bg-primary-500 peer-checked:border-primary-500 transition-all duration-200"></div>
                                    <svg class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-800 transition-colors">Ingat saya</span>
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" 
                                class="relative w-full py-4 px-6 bg-gradient-to-r from-primary-500 via-primary-600 to-emerald-600 text-white font-bold rounded-2xl hover:from-primary-600 hover:via-primary-700 hover:to-emerald-700 transition-all duration-300 shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 hover:-translate-y-0.5 overflow-hidden group mt-2">
                            <span class="relative z-10 flex items-center justify-center">
                                <span>Masuk</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </span>
                            <div class="absolute inset-0 btn-shimmer"></div>
                        </button>
                    </form>
                    
                    <!-- Help Link -->
                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                        <p class="text-sm text-gray-500">
                            Butuh bantuan? 
                            <a href="#" class="text-primary-600 font-semibold hover:text-primary-700 transition-colors hover:underline decoration-2 underline-offset-2">Hubungi Admin</a>
                        </p>
                    </div>
                </div>
                
                <!-- Footer -->
                <p class="text-center text-gray-400 lg:text-gray-500 text-sm mt-8 animate-fade-in">
                    &copy; {{ date('Y') }} BUMDes Somogede. Dibuat dengan 
                    <span class="text-rose-500">❤️</span> untuk kemajuan desa.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>

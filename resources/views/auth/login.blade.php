<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('news_portal.site.name', 'Info Lantas Mojokerto') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-[100dvh] bg-[#f0f2f5] flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-200">
                {{-- Header --}}
                <div class="bg-red-700 p-10 text-center text-white">
                    <div class="w-24 h-24 bg-white rounded-2xl mx-auto mb-6 flex items-center justify-center shadow-lg overflow-hidden">
                        <img loading="lazy" src="{{ asset('LogoBaruILM.png') }}" alt="Logo" class="w-full h-full object-contain p-2">
                    </div>
                    <h1 class="text-3xl font-black uppercase tracking-tight">LOGIN</h1>
                    <p class="text-red-100 text-[10px] mt-1 font-bold uppercase tracking-[0.3em]">Portal Info Lantas Mojokerto</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="p-8 space-y-6">
                    @csrf

                    {{-- Error Messages --}}
                    @if($errors->any())
                    <div class="bg-red-50 text-red-600 p-3 rounded text-sm font-bold text-center">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    {{-- Session Status --}}
                    @if(session('status'))
                    <div class="bg-green-50 text-green-600 p-3 rounded text-sm font-bold text-center">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{-- Email --}}
                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Email Address</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition-all font-medium text-sm"
                                placeholder="Masukkan email">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="space-y-2">
                        <label for="password" class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                class="w-full pl-10 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition-all font-medium text-sm"
                                placeholder="Masukkan password">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-black py-4 rounded-lg shadow-lg shadow-red-900/20 transition-all active:scale-[0.98] uppercase tracking-widest text-sm flex items-center justify-center gap-2 group">
                        Login Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>

                    {{-- Back to Home --}}
                    <a href="{{ route('home') }}" class="w-full flex items-center justify-center gap-2 text-gray-500 hover:text-red-700 font-black uppercase text-[11px] tracking-widest transition-colors bg-gray-50 py-3 rounded-lg border border-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="rotate-180"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        Kembali Ke Beranda
                    </a>
                </form>
            </div>
            <p class="text-center text-gray-400 text-[10px] mt-8 font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} Info Lantas Mojokerto &bull; Unified Login System
            </p>
        </div>
    </div>
</body>
</html>

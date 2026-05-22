<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('news_portal.site.name', 'Info Lantas Mojokerto') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
        {{-- Sidebar --}}
        <aside class="w-72 bg-gray-900 flex flex-col shrink-0 hidden lg:flex">
            {{-- Logo --}}
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3">
                    <img loading="lazy" src="{{ asset('LogoBaruILM.png') }}" alt="Logo" class="w-10 h-10 object-contain" onerror="this.style.display='none'">
                    <div>
                        <h2 class="text-white font-black text-[11px] uppercase tracking-tighter leading-tight">Info Lantas</h2>
                        <p class="text-[9px] font-bold text-red-600 uppercase tracking-widest leading-none">Mojokerto</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 p-4 overflow-y-auto" x-data="{ openMenu: '' }">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md mb-1 {{ request()->routeIs('admin.dashboard') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>Dashboard</span>
                </a>

                {{-- Modul Berita --}}
                <div class="mb-1" x-data="{ open: {{ request()->routeIs('admin.news.*') && !request('breaking') && !request('category') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md {{ request()->routeIs('admin.news.*') && !request('breaking') && !request('category') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            <span>Modul Berita</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform" :class="open ? 'rotate-180' : ''"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1 border-l-2 border-gray-800 pl-2">
                        <a href="{{ route('admin.news.create') }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request()->routeIs('admin.news.create') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Tambah Postingan</a>
                        <a href="{{ route('admin.news.index') }}" class="flex items-center justify-between px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request()->routeIs('admin.news.index') && !request('mine') && !request('status') && !request('featured') && !request('breaking') && !request('category') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                            <span>• Semua Postingan</span>
                            @php $totalNewsCount = \App\Models\News::count(); @endphp
                            <span class="bg-green-500 text-white px-2 py-0.5 rounded text-[8px] font-black">{{ $totalNewsCount > 1000 ? round($totalNewsCount/1000) . 'rb' : $totalNewsCount }}</span>
                        </a>
                        <a href="{{ route('admin.news.index', ['mine' => 1]) }}" class="flex items-center justify-between px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request('mine') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                            <span>• Postingan dimiliki</span>
                            @php $myNewsCount = \App\Models\News::where('author_id', auth()->id())->count(); @endphp
                            <span class="bg-blue-500 text-white px-2 py-0.5 rounded text-[8px] font-black">{{ $myNewsCount }}</span>
                        </a>
                        <a href="{{ route('admin.news.index', ['status' => 'draft']) }}" class="flex items-center justify-between px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request('status') == 'draft' ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                            <span>• Draf</span>
                            @php $draftCount = \App\Models\News::where('status', 'draft')->count(); @endphp
                            <span class="bg-red-500 text-white px-2 py-0.5 rounded text-[8px] font-black">{{ $draftCount }}</span>
                        </a>
                        <a href="{{ route('admin.news.index', ['featured' => 1]) }}" class="flex items-center justify-between px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request('featured') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                            <span>• Berita pilihan</span>
                            @php $featuredCount = \App\Models\News::where('is_featured', true)->count(); @endphp
                            <span class="bg-yellow-500 text-white px-2 py-0.5 rounded text-[8px] font-black">{{ $featuredCount > 1000 ? round($featuredCount/1000) . 'rb' : $featuredCount }}</span>
                        </a>
                    </div>
                </div>



                {{-- Modul Identitas (Admin only) --}}
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.web.identitas') }}" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md mb-1 {{ request()->routeIs('admin.web.identitas') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span>Modul Identitas</span>
                </a>

                {{-- Modul Breaking News (Admin only) --}}
                <a href="{{ route('admin.news.index', ['breaking' => 1]) }}" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md mb-1 {{ request('breaking') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    <span>Modul Breaking News</span>
                </a>
                @endif

                {{-- Modul Kelana Kota --}}
                <a href="{{ route('admin.galleries.index') }}" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md mb-1 {{ request()->routeIs('admin.galleries.*') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    <span>Modul Kelana Kota</span>
                </a>

                {{-- Modul Info Lalin --}}
                <a href="{{ route('admin.info-lalin.index') }}" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md mb-1 {{ request()->routeIs('admin.info-lalin.*') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                    <span>Modul Info Lalin</span>
                </a>

                {{-- Modul Video --}}
                <a href="{{ route('admin.videos.index') }}" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md mb-1 {{ request()->routeIs('admin.videos.*') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
                    <span>Modul Video</span>
                </a>

                {{-- Modul Web (with submenu) - Admin only --}}
                @if(auth()->user()->isAdmin())
                <div class="mb-1" x-data="{ open: {{ (request()->routeIs('admin.web.*') && !request()->routeIs('admin.web.identitas')) || request()->routeIs('admin.pages.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md {{ (request()->routeIs('admin.web.*') && !request()->routeIs('admin.web.identitas')) || (request()->routeIs('admin.pages.*') && !request()->routeIs('admin.pages.create')) || (request()->routeIs('admin.categories.*') && !request('breaking') && !request('category')) ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <span>Modul Web</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform" :class="open ? 'rotate-180' : ''"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1 border-l-2 border-gray-800 pl-2">
                        <a href="{{ route('admin.web.logo') }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request()->routeIs('admin.web.logo') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Logo</a>
                        <a href="{{ route('admin.web.popup') }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request()->routeIs('admin.web.popup') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Popup</a>
                        <a href="{{ route('admin.web.tema') }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request()->routeIs('admin.web.tema') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Tema</a>
                        <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request()->routeIs('admin.categories.*') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Menu</a>
                        <a href="{{ route('admin.pages.index') }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request()->routeIs('admin.pages.*') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Redaksi</a>
                    </div>
                </div>

                {{-- Modul Iklan (with submenu) --}}
                <div class="mb-1" x-data="{ open: {{ request()->routeIs('admin.advertisements.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md {{ request()->routeIs('admin.advertisements.*') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <span>Modul Iklan</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform" :class="open ? 'rotate-180' : ''"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1 border-l-2 border-gray-800 pl-2">
                        <a href="{{ route('admin.advertisements.index', ['position' => 'top']) }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request('position') == 'top' ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Paling Atas</a>
                        <a href="{{ route('admin.advertisements.index', ['position' => 'content']) }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request('position') == 'content' ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Atas</a>
                        <a href="{{ route('admin.advertisements.index', ['position' => 'sidebar']) }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request('position') == 'sidebar' ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Kanan Tengah</a>
                        <a href="{{ route('admin.advertisements.index') }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request()->routeIs('admin.advertisements.index') && !request('position') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Semua Iklan</a>
                        <a href="{{ route('admin.advertisements.index', ['position' => 'footer']) }}" class="block px-4 py-2 text-[11px] font-bold uppercase tracking-tight {{ request('position') == 'footer' ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">• Bawah</a>
                    </div>
                </div>

                {{-- Modul Kata Jorok --}}
                <a href="{{ route('admin.kata-jorok') }}" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md mb-1 {{ request()->routeIs('admin.kata-jorok') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><line x1="9" y1="10" x2="15" y2="10"/></svg>
                    <span>Modul Kata Jorok</span>
                </a>
                @endif

                {{-- Modul Redaktur (for redaktur role) --}}
                @if(auth()->user()->isAdmin() || auth()->user()->isRedaktur())
                <a href="{{ route('admin.users.index') }}" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold uppercase tracking-tight transition-all rounded-md mb-1 {{ request()->routeIs('admin.users.*') ? 'bg-red-700 text-white shadow-lg shadow-red-900/40' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>Modul Redaktur</span>
                </a>
                @endif
            </nav>

            {{-- Logout --}}
            <div class="p-4 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-gray-500 hover:text-white transition-all uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Logout Session
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto flex flex-col">
            {{-- Header --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-10 shadow-sm">
                <div class="flex items-center gap-4">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        {{ auth()->user()->isAdmin() ? 'Admin Panel' : 'Redaktur Panel' }} &bull; <span class="text-red-600">v2.0.1</span>
                    </p>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-[10px] font-black text-gray-600 hover:text-red-600 uppercase tracking-widest transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Lihat Web
                    </a>
                    <div class="h-4 w-[1px] bg-gray-200"></div>
                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        <div class="text-right hidden sm:block">
                            <p class="text-[12px] font-black text-gray-800 uppercase leading-none">{{ auth()->user()->nickname ?: auth()->user()->name }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ auth()->user()->role === 'admin' ? 'ADMIN' : 'REDAKTUR' }}</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </a>
                </div>
            </header>

            {{-- Content Area --}}
            <div class="p-8 overflow-y-auto h-full">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>

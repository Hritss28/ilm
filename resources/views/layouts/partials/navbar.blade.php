@php
    $categories = app(\App\Services\CacheService::class)->getCategoriesMenu();
@endphp

<nav class="bg-white sticky top-0 z-50 shadow-sm border-b border-gray-200" x-data="{ mobileOpen: false }">
    <div class="container-custom py-4 flex items-center justify-between gap-4">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="shrink-0 flex items-center gap-2 md:gap-3">
            <img src="{{ asset('LogoBaruILM.png') }}" alt="Info Lantas Mojokerto Logo" class="object-contain w-12 h-12 md:w-20 md:h-20" onerror="this.style.display='none'">
            <div class="flex flex-col -space-y-1">
                <span class="font-black text-base md:text-2xl leading-none text-[#1a1a1a] tracking-tighter uppercase whitespace-nowrap">INFO LANTAS</span>
                <span class="font-black text-base md:text-2xl leading-none text-red-600 tracking-tighter uppercase">MOJOKERTO</span>
                <span class="text-[10px] md:text-xs font-medium text-gray-500 italic mt-1 whitespace-nowrap tracking-tight">Dari Kita Untuk Kita</span>
            </div>
        </a>

        {{-- Desktop Main Menu --}}
        <div class="hidden lg:flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-[13px] font-bold tracking-wider transition-colors {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-900 hover:text-primary' }}">HOME</a>
            <a href="{{ route('news.search') }}" class="text-[13px] font-bold tracking-wider transition-colors {{ request()->routeIs('news.search') && !request('q') ? 'text-primary' : 'text-gray-900 hover:text-primary' }}">NEWS</a>
            <a href="{{ route('gallery.index') }}" class="text-[13px] font-bold tracking-wider transition-colors {{ request()->routeIs('gallery.*') ? 'text-primary' : 'text-gray-900 hover:text-primary' }}">POTRET KELANA KOTA</a>
            <a href="{{ route('video.index') }}" class="text-[13px] font-bold tracking-wider transition-colors {{ request()->routeIs('video.*') ? 'text-primary' : 'text-gray-900 hover:text-primary' }}">VIDEO</a>
            <a href="{{ route('infolalin') }}" class="text-[13px] font-bold tracking-wider transition-colors {{ request()->routeIs('infolalin') ? 'text-primary' : 'text-gray-900 hover:text-primary' }}">INFO LALIN</a>
            {{-- Search Button --}}
            <form action="{{ route('news.search') }}" method="GET" class="relative">
                <button type="submit" class="text-gray-900 hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
            </form>
        </div>

        {{-- Mobile Hamburger --}}
        <div class="lg:hidden">
            <button @click="mobileOpen = !mobileOpen" class="p-2 text-gray-900">
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                <svg x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    </div>

    {{-- Sub Menu Desktop --}}
    <div class="bg-white border-t border-gray-100 overflow-x-auto no-scrollbar hidden md:block">
        <div class="container-custom flex items-center gap-6 py-2.5 whitespace-nowrap text-[12px] font-medium text-gray-600">
            <a href="{{ route('about') }}" class="hover:text-primary transition-colors {{ request()->routeIs('about') ? 'text-primary font-bold' : '' }}">Tentang Kami</a>
            @foreach($categories as $cat)
                <a href="{{ route('news.category', $cat->slug) }}" class="hover:text-primary transition-colors {{ request()->is('category/'.$cat->slug) ? 'text-primary font-bold' : '' }}">{{ $cat->name }}</a>
            @endforeach
            <a href="{{ route('redaksi') }}" class="hover:text-primary transition-colors {{ request()->routeIs('redaksi') ? 'text-primary font-bold' : '' }}">Redaksi</a>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen" x-transition class="lg:hidden bg-white border-t border-gray-100 absolute left-0 right-0 top-full shadow-xl z-50">
        <div class="container-custom py-4 space-y-4">
            <div class="flex flex-col gap-4 border-b border-gray-50 pb-4">
                <a href="{{ route('home') }}" @click="mobileOpen = false" class="text-sm font-bold tracking-wider {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-900' }}">HOME</a>
                <a href="{{ route('news.search') }}" @click="mobileOpen = false" class="text-sm font-bold tracking-wider {{ request()->routeIs('news.search') ? 'text-primary' : 'text-gray-900' }}">NEWS</a>
                <a href="{{ route('gallery.index') }}" @click="mobileOpen = false" class="text-sm font-bold tracking-wider {{ request()->routeIs('gallery.*') ? 'text-primary' : 'text-gray-900' }}">POTRET KELANA KOTA</a>
                <a href="{{ route('video.index') }}" @click="mobileOpen = false" class="text-sm font-bold tracking-wider {{ request()->routeIs('video.*') ? 'text-primary' : 'text-gray-900' }}">VIDEO</a>
                <a href="{{ route('infolalin') }}" @click="mobileOpen = false" class="text-sm font-bold tracking-wider {{ request()->routeIs('infolalin') ? 'text-primary' : 'text-gray-900' }}">INFO LALIN</a>
            </div>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
                <a href="{{ route('about') }}" @click="mobileOpen = false" class="text-xs font-medium {{ request()->routeIs('about') ? 'text-primary' : 'text-gray-500' }}">Tentang Kami</a>
                @foreach($categories as $cat)
                    <a href="{{ route('news.category', $cat->slug) }}" @click="mobileOpen = false" class="text-xs font-medium text-gray-500 hover:text-primary">{{ $cat->name }}</a>
                @endforeach
                <a href="{{ route('redaksi') }}" @click="mobileOpen = false" class="text-xs font-medium {{ request()->routeIs('redaksi') ? 'text-primary' : 'text-gray-500' }}">Redaksi</a>
            </div>
        </div>
    </div>
</nav>

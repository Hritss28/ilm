@php
    $socialFacebook = \App\Models\WebSetting::get('social_facebook', 'https://www.facebook.com/InfoLantasMojokerto');
    $socialInstagram= \App\Models\WebSetting::get('social_instagram', 'https://www.instagram.com/infolantasmojokerto');
    $socialYoutube  = \App\Models\WebSetting::get('social_youtube', 'https://www.youtube.com/@InfoLantasMojokerto');
    $socialTiktok   = \App\Models\WebSetting::get('social_tiktok', 'https://www.tiktok.com/@info.lantas.mojokerto');
@endphp
<div class="bg-white border-b border-gray-100 py-1.5 hidden md:block">
    <div class="container-custom flex justify-between items-center text-[11px] text-gray-500 font-medium">
        <div>
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y') }}
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-5">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-[0.1em] text-gray-600">Halo, <span class="text-primary">{{ auth()->user()->name }}</span></span>
                        <div class="h-3 w-[1px] bg-gray-200"></div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-all flex items-center gap-1.5 group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                <span class="text-[10px] font-black uppercase tracking-[0.1em]">Logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-primary transition-all flex items-center gap-1.5 group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        <span class="text-[10px] font-black uppercase tracking-[0.1em]">Login</span>
                    </a>
                @endauth
                <div class="h-3 w-[1px] bg-gray-200"></div>
                <div class="flex items-center gap-4">
                    <a href="{{ $socialFacebook }}" target="_blank" rel="noreferrer" class="hover:text-primary transition-all hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="{{ $socialInstagram }}" target="_blank" rel="noreferrer" class="hover:text-primary transition-all hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="{{ $socialYoutube }}" target="_blank" rel="noreferrer" class="hover:text-primary transition-all hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/></svg>
                    </a>
                    <a href="{{ $socialTiktok }}" target="_blank" rel="noreferrer" class="hover:text-primary transition-all hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $footerAds = app(\App\Services\CacheService::class)->getActiveAds('footer');
@endphp

@if($footerAds->count() > 0)
<div class="container-custom py-6">
    <a href="{{ $footerAds[0]->link_url }}" target="_blank" rel="noopener" class="block w-full bg-gray-100 border border-gray-200 overflow-hidden relative group cursor-pointer shadow-sm">
        <img loading="lazy" src="{{ Storage::url($footerAds[0]->image_url) }}" alt="{{ $footerAds[0]->title }}" class="w-full h-auto group-hover:scale-105 transition-transform duration-1000">
    </a>
</div>
@endif

<footer class="bg-white border-t border-gray-200 pt-16">
    <div class="container-custom">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 pb-16">
            {{-- Logo & Description --}}
            <div class="flex flex-col gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img loading="lazy" src="{{ asset('LogoBaruILM.png') }}" alt="Logo" class="w-12 h-12 object-contain" onerror="this.style.display='none'">
                    <div class="flex flex-col -space-y-1">
                        <span class="font-black text-lg leading-none text-[#1a1a1a] tracking-tighter uppercase">INFO LANTAS</span>
                        <span class="font-black text-lg leading-none text-red-600 tracking-tighter uppercase">MOJOKERTO</span>
                    </div>
                </a>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Info Lantas Mojokerto adalah portal berita dan informasi lalu lintas terpercaya di wilayah Mojokerto Raya. Menyajikan kabar terkini, update lantas, dan potensi daerah.
                </p>
                <div class="flex items-center gap-4">
                    <a href="https://www.facebook.com/InfoLantasMojokerto" target="_blank" rel="noopener noreferrer" class="p-2 border border-gray-200 rounded-full hover:border-primary hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/infolantasmojokerto" target="_blank" rel="noopener noreferrer" class="p-2 border border-gray-200 rounded-full hover:border-primary hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@InfoLantasMojokerto" target="_blank" rel="noopener noreferrer" class="p-2 border border-gray-200 rounded-full hover:border-primary hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/></svg>
                    </a>
                    <a href="https://www.tiktok.com/@info.lantas.mojokerto" target="_blank" rel="noopener noreferrer" class="p-2 border border-gray-200 rounded-full hover:border-primary hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                    </a>
                </div>
            </div>

            {{-- Links --}}
            <div>
                <h3 class="text-sm font-bold border-l-4 border-primary pl-4 mb-6 uppercase tracking-widest">TAUTAN</h3>
                <ul class="grid grid-cols-2 gap-4 text-xs font-bold text-gray-500">
                    <li><a href="{{ route('gallery.index') }}" class="hover:text-primary transition-colors">KELANA KOTA</a></li>
                    <li><a href="{{ route('news.category', 'olahraga') }}" class="hover:text-primary transition-colors">OLAHRAGA</a></li>
                    <li><a href="{{ route('news.category', 'politik') }}" class="hover:text-primary transition-colors">POLITIK</a></li>
                    <li><a href="{{ route('news.category', 'ekonomi') }}" class="hover:text-primary transition-colors">EKONOMI</a></li>
                    <li><a href="{{ route('news.category', 'regional') }}" class="hover:text-primary transition-colors">REGIONAL</a></li>
                    <li><a href="{{ route('news.category', 'nasional') }}" class="hover:text-primary transition-colors">NASIONAL</a></li>
                </ul>
            </div>

            {{-- Popular News --}}
            <div class="lg:col-span-2">
                <h3 class="text-sm font-bold border-l-4 border-primary pl-4 mb-6 uppercase tracking-widest">BERITA TERPOPULER</h3>
                @php
                    $footerPopular = app(\App\Services\CacheService::class)->getPopularNews()->take(2);
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($footerPopular as $news)
                    <a href="{{ route('news.show', $news->slug) }}" class="flex gap-3 group cursor-pointer">
                        <div class="w-16 h-16 bg-gray-100 shrink-0 overflow-hidden">
                            @if($news->thumbnail)
                                <img loading="lazy" src="{{ Storage::url($news->thumbnail) }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" loading="lazy">
                            @endif
                        </div>
                        <div class="flex flex-col gap-1">
                            <h4 class="text-[11px] font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2">{{ $news->title }}</h4>
                            <span class="text-[10px] text-gray-400">{{ $news->published_at?->translatedFormat('j F Y') }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-100 py-6 bg-gray-50">
        <div class="container-custom flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            <span>INFO LANTAS MOJOKERTO &copy; {{ date('Y') }}</span>
            <div class="flex gap-6">
                <a href="{{ route('about') }}" class="hover:text-primary transition-colors">TENTANG KAMI</a>
                <a href="{{ route('redaksi') }}" class="hover:text-primary transition-colors">REDAKSI</a>
                <a href="{{ route('about') }}" class="hover:text-primary transition-colors">DISCLAIMER</a>
            </div>
        </div>
    </div>
</footer>

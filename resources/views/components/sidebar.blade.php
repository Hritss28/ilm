@php
    $cacheService = app(\App\Services\CacheService::class);
    $popularNews = $cacheService->getPopularNews();
    $recentNews = $cacheService->getRecentNews();
    $sidebarAds = $cacheService->getActiveAds('sidebar');
@endphp

<aside class="flex flex-col gap-10">
    {{-- Sidebar Ad Top --}}
    @if($sidebarAds->count() > 0)
    <div class="flex flex-col gap-6">
        @foreach($sidebarAds->take(2) as $ad)
        <a href="{{ $ad->link_url }}" target="_blank" rel="noopener" class="bg-gray-100 aspect-[4/3] flex items-center justify-center text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] border border-gray-200 relative group cursor-pointer overflow-hidden shadow-sm">
            <img src="{{ Storage::url($ad->image_url) }}" alt="{{ $ad->title }}" class="w-full h-full object-cover opacity-60 group-hover:scale-110 group-hover:opacity-100 transition-all duration-1000" loading="lazy">
            <div class="absolute top-2 right-2 bg-black/40 text-[8px] text-white px-1 font-bold">ADVERTISEMENT</div>
        </a>
        @endforeach
    </div>
    @endif

    {{-- Popular News --}}
    <div class="bg-white p-6 border border-gray-100 shadow-sm">
        <div class="mb-6 border-l-4 border-primary pl-4">
            <h3 class="text-sm font-bold uppercase tracking-widest text-navy-900">BERITA POPULER</h3>
        </div>
        <div class="space-y-5">
            @foreach($popularNews->take(5) as $index => $news)
            <a href="{{ route('news.show', $news->slug) }}" class="group cursor-pointer flex gap-4">
                <span class="text-lg font-black text-gray-100 group-hover:text-primary transition-colors min-w-[24px] text-center leading-none">
                    {{ $index + 1 }}
                </span>
                <p class="text-xs font-bold text-gray-800 group-hover:text-primary transition-colors leading-relaxed line-clamp-2">
                    {{ $news->title }}
                </p>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Berita Pilihan --}}
    <div class="bg-white p-6 border border-gray-100 shadow-sm">
        <div class="mb-6 border-l-4 border-primary pl-4">
            <h3 class="text-sm font-bold uppercase tracking-widest text-navy-900">BERITA PILIHAN</h3>
        </div>
        <div class="space-y-5">
            @foreach($recentNews->take(5) as $index => $news)
            <a href="{{ route('news.show', $news->slug) }}" class="group cursor-pointer flex gap-4">
                <span class="text-lg font-black text-gray-100 group-hover:text-primary transition-colors min-w-[24px] text-center leading-none">
                    {{ $index + 1 }}
                </span>
                <p class="text-xs font-bold text-gray-800 group-hover:text-primary transition-colors leading-relaxed line-clamp-2">
                    {{ $news->title }}
                </p>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Sticky Ad --}}
    @if($sidebarAds->count() > 2)
    <div class="sticky top-28 bg-gray-100 border border-gray-200 aspect-[3/4] overflow-hidden relative group cursor-pointer shadow-sm">
        <a href="{{ $sidebarAds[2]->link_url }}" target="_blank" rel="noopener">
            <img src="{{ Storage::url($sidebarAds[2]->image_url) }}" alt="{{ $sidebarAds[2]->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" loading="lazy">
            <div class="absolute top-2 right-2 bg-black/40 text-[8px] text-white px-1 font-bold">ADVERTISEMENT</div>
        </a>
    </div>
    @endif
</aside>

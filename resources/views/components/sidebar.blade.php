@php
    $cacheService = app(\App\Services\CacheService::class);
    $popularNews = $cacheService->getPopularNews();
    $recentNews = $cacheService->getRecentNews();
    $sidebarAds = $cacheService->getActiveAds('sidebar');
@endphp

<aside class="flex flex-col gap-10 h-full">
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

    {{-- Sidebar Ads (Sticky) --}}
    <div class="sticky top-28 flex flex-col gap-6 pb-10">
        @if($sidebarAds->count() > 0)
            @foreach($sidebarAds->take(3) as $ad)
            <a href="{{ $ad->link_url }}" target="_blank" rel="noopener" class="bg-gray-100 flex items-center justify-center border border-gray-200 relative group cursor-pointer overflow-hidden shadow-sm">
                <img loading="lazy" src="{{ Storage::url($ad->image_url) }}" alt="{{ $ad->title }}" class="w-full h-auto group-hover:scale-105 transition-all duration-1000" loading="lazy">
            </a>
            @endforeach
        @endif
    </div>
</aside>

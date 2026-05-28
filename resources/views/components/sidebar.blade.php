@php
    $cacheService = app(\App\Services\CacheService::class);
    $popularAll = $cacheService->getPopularAll(10);
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
            @foreach($popularAll->take(5) as $index => $item)
            <a href="{{ $item->url }}" class="group cursor-pointer flex gap-4">
                <span class="text-lg font-black text-gray-100 group-hover:text-primary transition-colors min-w-[24px] text-center leading-none">
                    {{ $index + 1 }}
                </span>
                <div class="flex flex-col gap-1">
                    @if($item->type === 'video')
                        <span class="text-[9px] font-bold uppercase tracking-wider text-white bg-red-500 px-1.5 py-0.5 rounded self-start">VIDEO</span>
                    @elseif($item->type === 'gallery')
                        <span class="text-[9px] font-bold uppercase tracking-wider text-white bg-emerald-600 px-1.5 py-0.5 rounded self-start">POTRET</span>
                    @else
                        <span class="text-[9px] font-bold uppercase tracking-wider text-white bg-primary px-1.5 py-0.5 rounded self-start">{{ $item->label }}</span>
                    @endif
                    <p class="text-xs font-bold text-gray-800 group-hover:text-primary transition-colors leading-relaxed line-clamp-2">
                        {{ $item->title }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Weather Widget --}}
    <div id="weather-widget" class="overflow-hidden shadow-md" style="background:linear-gradient(160deg,#1e40af 0%,#2563eb 50%,#3b82f6 100%);position:relative;">

        {{-- Decorative blobs --}}
        <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.05);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-40px;left:-20px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,0.03);pointer-events:none;"></div>

        {{-- Loading skeleton --}}
        <div id="weather-loading" class="animate-pulse px-5 pb-5" style="position:relative;z-index:1;">
            <div style="display:grid;grid-template-columns:1fr 1px 1fr;gap:0;">
                <div class="text-center py-2">
                    <div style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.1);margin:0 auto 8px;"></div>
                    <div style="width:48px;height:22px;border-radius:4px;background:rgba(255,255,255,0.1);margin:0 auto 6px;"></div>
                    <div style="width:36px;height:10px;border-radius:3px;background:rgba(255,255,255,0.07);margin:0 auto;"></div>
                </div>
                <div style="background:rgba(255,255,255,0.08);"></div>
                <div class="text-center py-2">
                    <div style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.1);margin:0 auto 8px;"></div>
                    <div style="width:48px;height:22px;border-radius:4px;background:rgba(255,255,255,0.1);margin:0 auto 6px;"></div>
                    <div style="width:36px;height:10px;border-radius:3px;background:rgba(255,255,255,0.07);margin:0 auto;"></div>
                </div>
            </div>
        </div>

        {{-- Two-city display --}}
        <div id="weather-cities" class="hidden" style="position:relative;z-index:1;">
            <div style="display:grid;grid-template-columns:1fr 1px 1fr;">
                {{-- City 0 --}}
                <div id="wcity-0" class="text-center px-3 pb-5 pt-5"></div>
                {{-- Divider --}}
                <div style="background:rgba(255,255,255,0.1);"></div>
                {{-- City 1 --}}
                <div id="wcity-1" class="text-center px-3 pb-5 pt-5"></div>
            </div>
        </div>

        {{-- Error state --}}
        <p id="weather-error" class="hidden text-center py-5" style="font-size:11px;color:rgba(255,255,255,0.35);">Gagal memuat data cuaca.</p>
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

@push('scripts')
<script>
(function () {
    const WEATHER_URL = '{{ route("api.weather") }}';

    const EMOJI = {
        sunny:   '☀️',
        cloudy:  '⛅',
        fog:     '🌫️',
        drizzle: '🌦️',
        rain:    '🌧️',
        storm:   '⛈️',
        snow:    '🌨️',
        unknown: '🌡️',
    };

    function getEmoji(condition) {
        const c = condition.toLowerCase();
        if (c.includes('cerah'))                           return EMOJI.sunny;
        if (c.includes('berawan'))                         return EMOJI.cloudy;
        if (c.includes('kabut'))                           return EMOJI.fog;
        if (c.includes('gerimis'))                         return EMOJI.drizzle;
        if (c.includes('hujan') || c.includes('deras'))    return EMOJI.rain;
        if (c.includes('badai') || c.includes('petir'))    return EMOJI.storm;
        if (c.includes('salju') || c.includes('bersalju')) return EMOJI.snow;
        return EMOJI.unknown;
    }

    function renderCity(w) {
        const temp = w.temperature !== null ? `${w.temperature}°` : '--';
        const hum  = w.humidity   !== null ? `${w.humidity}%`     : '--';
        const wind = w.windspeed  !== null ? `${w.windspeed}`     : '--';
        const shortLabel = w.label.replace('Kota ', 'Kota\n').replace('Kab. ', 'Kab.\n');

        return `
            <p style="font-size:9px;font-weight:700;letter-spacing:.08em;color:rgba(255,255,255,0.45);text-transform:uppercase;margin-bottom:4px;">${w.label}</p>
            <div style="font-size:38px;line-height:1;margin-bottom:2px;filter:drop-shadow(0 3px 8px rgba(0,0,0,0.4));">${getEmoji(w.condition)}</div>
            <div style="font-size:28px;font-weight:900;color:#fff;letter-spacing:-1px;line-height:1;">${temp}</div>
            <div style="font-size:10px;font-weight:600;color:rgba(255,255,255,0.65);margin-top:3px;margin-bottom:8px;">${w.condition}</div>
            <div style="display:flex;flex-direction:column;gap:3px;">
                <div style="display:flex;align-items:center;justify-content:center;gap:3px;">
                    <svg style="width:9px;height:9px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="rgba(147,197,253,0.8)" stroke-width="2"><path stroke-linecap="round" d="M12 2C6.5 11 4 15.5 4 18a8 8 0 0016 0c0-2.5-2.5-7-8-16z"/></svg>
                    <span style="font-size:9px;font-weight:700;color:rgba(255,255,255,0.5);">${hum}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:center;gap:3px;">
                    <svg style="width:9px;height:9px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="rgba(147,197,253,0.8)" stroke-width="2"><path stroke-linecap="round" d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/></svg>
                    <span style="font-size:9px;font-weight:700;color:rgba(255,255,255,0.5);">${wind} km/j</span>
                </div>
            </div>`;
    }

    async function loadWeather() {
        try {
            const res  = await fetch(WEATHER_URL, { cache: 'no-store' });
            if (!res.ok) throw new Error();
            const data = await res.json();

            data.forEach((w, i) => {
                const el = document.getElementById(`wcity-${i}`);
                if (el) el.innerHTML = renderCity(w);
            });

            document.getElementById('weather-loading').classList.add('hidden');
            document.getElementById('weather-cities').classList.remove('hidden');
        } catch (e) {
            document.getElementById('weather-loading').classList.add('hidden');
            const err = document.getElementById('weather-error');
            if (err) err.classList.remove('hidden');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadWeather);
    } else {
        loadWeather();
    }
})();
</script>
@endpush

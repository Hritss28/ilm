@extends('layouts.app')

@section('content')
{{-- Unified Grid Layout --}}
<div class="container-custom grid grid-cols-1 lg:grid-cols-3 gap-10 py-4">
    {{-- Left Column (Span 2) --}}
    <div class="lg:col-span-2 flex flex-col gap-6">
        
        {{-- Left: Main Headline --}}
        <div class="flex flex-col gap-4">
            @if($featuredNews->count() > 0)
            <a href="{{ route('news.show', $featuredNews[0]->slug) }}" class="relative group overflow-hidden bg-navy-900 aspect-video lg:aspect-auto lg:h-[480px]">
                @if($featuredNews[0]->thumbnail)
                <img loading="lazy" src="{{ Storage::url($featuredNews[0]->thumbnail) }}" alt="{{ $featuredNews[0]->title }}" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700" loading="lazy">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent flex flex-col justify-end p-6 md:p-10 pointer-events-none">
                    <span class="bg-primary text-white text-[11px] font-bold px-2 py-0.5 w-fit mb-4 uppercase tracking-widest">
                        HEADLINE
                    </span>
                    <h1 class="text-white text-2xl md:text-4xl font-bold leading-tight mb-4 group-hover:text-amber-400 transition-colors pointer-events-auto">
                        {{ $featuredNews[0]->title }}
                    </h1>
                    <p class="text-gray-300 text-sm md:text-base line-clamp-2 max-w-2xl font-light">
                        {{ $featuredNews[0]->excerpt }}
                    </p>
                </div>
            </a>

            {{-- Sub headlines --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($featuredNews->skip(1)->take(2) as $featured)
                <a href="{{ route('news.show', $featured->slug) }}" class="bg-white p-4 border-l-4 border-primary hover:bg-gray-50 cursor-pointer transition-colors">
                    <h3 class="text-sm font-bold text-gray-900 line-clamp-2">{{ $featured->title }}</h3>
                </a>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Horizontal Ad --}}
        @if($contentAds->count() > 0)
        <div class="w-full py-2">
            <a href="{{ $contentAds[0]->link_url }}" target="_blank" rel="noopener" class="block w-full bg-gray-100 border border-gray-200 overflow-hidden relative group cursor-pointer shadow-sm">
                <img loading="lazy" src="{{ Storage::url($contentAds[0]->image_url) }}" alt="{{ $contentAds[0]->title }}" class="w-full h-auto group-hover:scale-105 transition-transform duration-1000" loading="lazy">
            </a>
        </div>
        @endif

        {{-- News Sections by Category --}}
        @foreach($categoryNews as $catSlug => $catData)
            @if($catData['news']->count() > 0)
            <section class="py-2 border-b border-gray-100">
                <div class="flex justify-between items-center mb-4 border-l-4 border-primary pl-4">
                    <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tighter">{{ $catData['name'] }}</h2>
                    <a href="{{ route('news.category', $catSlug) }}" class="flex items-center gap-1 text-[11px] font-bold text-gray-400 hover:text-primary transition-colors uppercase tracking-widest">
                        LIHAT SEMUA
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-8">
                    {{-- Main article --}}
                    @if($catData['news']->first())
                    @php $mainArticle = $catData['news']->first(); @endphp
                    <div class="w-full">
                        <a href="{{ route('news.show', $mainArticle->slug) }}" class="flex flex-col md:flex-row gap-8 group cursor-pointer">
                            <div class="md:w-[40%] aspect-[4/3] overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($mainArticle->thumbnail)
                                <img loading="lazy" src="{{ Storage::url($mainArticle->thumbnail) }}" alt="{{ $mainArticle->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                                @endif
                            </div>
                            <div class="md:w-[60%] flex flex-col py-2 pr-4 relative">
                                <div class="text-[11px] text-gray-400 font-bold mb-4 uppercase text-right md:-mt-1">{{ $mainArticle->published_at?->translatedFormat('l, j F Y | H:i') }} WIB</div>
                                <h3 class="text-xl lg:text-4xl font-black text-gray-900 group-hover:text-primary transition-colors mb-6 leading-[1.1]">{{ $mainArticle->title }}</h3>
                                <p class="text-gray-600 text-sm md:text-base line-clamp-3 leading-relaxed font-medium">{{ $mainArticle->excerpt }}</p>
                            </div>
                        </a>
                    </div>
                    @endif

                    {{-- Sub articles --}}
                    @if($catData['news']->count() > 1)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($catData['news']->skip(1)->take(3) as $article)
                        <a href="{{ route('news.show', $article->slug) }}" class="flex flex-col group cursor-pointer">
                            <div class="aspect-[4/3] overflow-hidden bg-gray-100 mb-3">
                                @if($article->thumbnail)
                                <img loading="lazy" src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                @endif
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">{{ $catData['name'] }}</span>
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-primary transition-colors leading-snug line-clamp-2 mb-2">{{ $article->title }}</h3>
                                <div class="text-[10px] text-gray-400 font-medium">{{ $article->published_at?->translatedFormat('j F Y') }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </section>
            @endif
        @endforeach
    </div>

    {{-- Right Column (Sidebar) --}}
    <div class="w-full lg:col-span-1 flex flex-col gap-10">
        {{-- Right: Ads Column (Hero Kanan) --}}
        @if($topAds->count() > 0)
        <div class="flex flex-col gap-6">
            @foreach($topAds->take(2) as $ad)
            <a href="{{ $ad->link_url }}" target="_blank" rel="noopener" class="bg-gray-100 flex items-center justify-center border border-gray-200 relative group cursor-pointer overflow-hidden shadow-sm">
                <img loading="lazy" src="{{ Storage::url($ad->image_url) }}" alt="{{ $ad->title }}" class="w-full h-auto group-hover:scale-105 transition-all duration-1000" loading="lazy">
            </a>
            @endforeach
        </div>
        @endif

        {{-- Sidebar --}}
        <x-sidebar />
    </div>
</div>
@endsection

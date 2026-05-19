@props(['article', 'layout' => 'vertical'])

@php
    $thumbnailUrl = $article->thumbnail_url ?? '/storage/thumbnails/default.webp';
@endphp

@if($layout === 'featured')
<article class="relative rounded-lg overflow-hidden shadow-lg group">
    <a href="{{ route('news.show', $article->slug) }}">
        <img src="{{ $thumbnailUrl }}" alt="{{ $article->title }}" class="w-full h-72 md:h-96 object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-6">
            @if($article->category)
                <span class="inline-block bg-primary text-white text-xs font-semibold px-2 py-0.5 rounded mb-2">
                    {{ $article->category->name }}
                </span>
            @endif
            <h2 class="text-white text-xl md:text-2xl font-bold leading-tight line-clamp-2">{{ $article->title }}</h2>
            <div class="flex items-center gap-3 mt-2 text-gray-300 text-xs">
                <span>{{ $article->author->name ?? 'Admin' }}</span>
                <span>&bull;</span>
                <span>{{ $article->published_at?->diffForHumans() ?? $article->created_at->diffForHumans() }}</span>
                <span>&bull;</span>
                <span>{{ number_format($article->views) }} views</span>
            </div>
        </div>
    </a>
</article>

@elseif($layout === 'horizontal')
<article class="flex gap-4 group">
    <a href="{{ route('news.show', $article->slug) }}" class="flex-shrink-0">
        <img src="{{ $thumbnailUrl }}" alt="{{ $article->title }}" class="w-32 h-24 md:w-40 md:h-28 object-cover rounded-lg group-hover:opacity-90 transition" loading="lazy">
    </a>
    <div class="flex flex-col justify-center min-w-0">
        @if($article->category)
            <span class="text-primary text-xs font-semibold mb-1">{{ $article->category->name }}</span>
        @endif
        <a href="{{ route('news.show', $article->slug) }}">
            <h3 class="text-sm md:text-base font-semibold text-gray-900 line-clamp-2 group-hover:text-primary transition">{{ $article->title }}</h3>
        </a>
        <div class="flex items-center gap-2 mt-1 text-gray-500 text-xs">
            <span>{{ $article->author->name ?? 'Admin' }}</span>
            <span>&bull;</span>
            <span>{{ $article->published_at?->diffForHumans() ?? $article->created_at->diffForHumans() }}</span>
            <span>&bull;</span>
            <span>{{ number_format($article->views) }} views</span>
        </div>
    </div>
</article>

@else {{-- vertical --}}
<article class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition">
    <a href="{{ route('news.show', $article->slug) }}">
        <img src="{{ $thumbnailUrl }}" alt="{{ $article->title }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
    </a>
    <div class="p-4">
        @if($article->category)
            <span class="inline-block bg-primary/10 text-primary text-xs font-semibold px-2 py-0.5 rounded mb-2">
                {{ $article->category->name }}
            </span>
        @endif
        <a href="{{ route('news.show', $article->slug) }}">
            <h3 class="font-semibold text-gray-900 line-clamp-2 group-hover:text-primary transition">{{ $article->title }}</h3>
        </a>
        <div class="flex items-center gap-2 mt-2 text-gray-500 text-xs">
            <span>{{ $article->author->name ?? 'Admin' }}</span>
            <span>&bull;</span>
            <span>{{ $article->published_at?->diffForHumans() ?? $article->created_at->diffForHumans() }}</span>
            <span>&bull;</span>
            <span>{{ number_format($article->views) }} views</span>
        </div>
    </div>
</article>
@endif

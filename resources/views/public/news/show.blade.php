@extends('layouts.app')

@section('content')
<div class="container-custom py-8">
    <div class="flex flex-col lg:flex-row gap-10">
        {{-- Article Content --}}
        <article class="flex-1">
            {{-- Category Badge --}}
            @if($article->category)
                <a href="{{ route('news.category', $article->category->slug) }}" class="inline-block text-[10px] font-bold text-primary uppercase tracking-widest mb-3">
                    {{ $article->category->name }}
                </a>
            @endif

            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">{{ $article->title }}</h1>

            {{-- Metadata --}}
            <div class="flex flex-wrap items-center gap-4 text-[11px] text-gray-500 mb-6 pb-4 border-b border-gray-100">
                <span class="flex items-center gap-1 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $article->author->name ?? 'Admin' }}
                </span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span class="font-medium">
                    {{ $article->published_at?->translatedFormat('l, j F Y | H:i') }} WIB
                </span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span class="font-medium">{{ number_format($article->views) }} views</span>
            </div>

            {{-- Thumbnail --}}
            @if($article->thumbnail)
            <div class="mb-6 aspect-video overflow-hidden rounded">
                <img loading="lazy" src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover" loading="lazy">
            </div>
            @endif

            {{-- Article Content --}}
            <div class="prose prose-lg max-w-none mb-8">
                @php
                    $content = $article->content;
                    // Replace YouTube iframes with clickable thumbnails
                    $content = preg_replace_callback('/<iframe[^>]+src="([^"]+)"[^>]*>.*?<\/iframe>/i', function($matches) {
                        $url = $matches[1];
                        $videoId = null;
                        
                        if (preg_match('/(?:youtube\.com\/(?:embed\/|watch\?v=)|youtu\.be\/)([^"&?\/]+)/i', $url, $idMatches)) {
                            $videoId = $idMatches[1];
                        }
                        
                        if ($videoId) {
                            return '<a href="https://www.youtube.com/watch?v='.$videoId.'" target="_blank" class="block relative aspect-video group overflow-hidden rounded bg-gray-900 mb-6 border border-gray-200">
                                <img src="https://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg" alt="YouTube Video" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center text-white shadow-lg">
                                        <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </a>';
                        }
                        return $matches[0];
                    }, $content);
                @endphp
                {!! $content !!}
            </div>

            {{-- Social Sharing --}}
            <div class="border-t border-gray-100 pt-6 mb-8">
                <h4 class="text-sm font-bold text-gray-700 mb-3">Bagikan Artikel:</h4>
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('news.show', $article->slug)) }}" target="_blank" rel="noopener"
                       class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition">
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('news.show', $article->slug)) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener"
                       class="flex items-center gap-2 bg-sky-500 text-white px-4 py-2 rounded-md text-sm hover:bg-sky-600 transition">
                        Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('news.show', $article->slug)) }}" target="_blank" rel="noopener"
                       class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700 transition">
                        WhatsApp
                    </a>
                </div>
            </div>

            {{-- Related News --}}
            @if($relatedNews->isNotEmpty())
            <section class="border-t border-gray-100 pt-8">
                <div class="border-l-4 border-primary pl-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tighter">Berita Terkait</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($relatedNews as $related)
                    <a href="{{ route('news.show', $related->slug) }}" class="flex flex-col group cursor-pointer">
                        <div class="w-full aspect-[4/3] overflow-hidden bg-gray-100 mb-3">
                            @if($related->thumbnail)
                            <img loading="lazy" src="{{ Storage::url($related->thumbnail) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            @if($related->category)
                                <span class="text-[10px] font-bold text-primary uppercase tracking-widest">{{ $related->category->name }}</span>
                                <span class="text-gray-300">&bull;</span>
                            @endif
                            <div class="flex items-center gap-1.5 text-gray-400 text-[12px] font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>{{ $related->published_at?->translatedFormat('l, j F Y | H:i') }} WIB</span>
                            </div>
                        </div>
                        <h4 class="font-bold text-gray-900 group-hover:text-primary transition-colors leading-snug line-clamp-2 text-lg">{{ $related->title }}</h4>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif
        </article>

        {{-- Sidebar --}}
        <div class="w-full lg:w-80">
            <x-sidebar />
        </div>
    </div>
</div>
@endsection

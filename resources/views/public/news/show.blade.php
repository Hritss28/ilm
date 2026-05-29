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
                                    <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-white shadow-lg">
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
                       class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-600 transition">
                        WhatsApp
                    </a>
                </div>
            </div>

            {{-- ═══════════ COMMENT SECTION ═══════════ --}}
            <section class="border-t border-gray-100 pt-8 mt-4" id="komentar">
                <div class="border-l-4 border-primary pl-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tighter">
                        Komentar
                        <span class="text-base font-black text-primary ml-1">({{ $article->comments->count() }})</span>
                    </h3>
                </div>

                {{-- Success Message --}}
                @if(session('comment_success'))
                    <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ session('comment_success') }}
                    </div>
                @endif

                {{-- Comment Form / Gate --}}
                @auth
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white text-sm font-black shrink-0">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ auth()->user()->name }}</span>
                        </div>
                        <form action="{{ route('comments.store', $article) }}" method="POST">
                            @csrf
                            <textarea name="body" id="comment-body" rows="3"
                                class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl px-4 py-3 outline-none focus:ring-2 focus:border-transparent transition-all resize-none @error('body') border-red-400 @enderror"
                                placeholder="Tulis komentar Anda di sini..."
                                style="focus:ring-color: var(--color-primary)">{{ old('body') }}</textarea>
                            @error('body')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="flex justify-end mt-3">
                                <button type="submit"
                                    class="px-6 py-2 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all active:scale-95 shadow-sm"
                                    style="background: var(--color-primary)">
                                    Kirim Komentar
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-gray-50 border border-dashed border-gray-300 rounded-2xl p-8 mb-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 text-gray-400"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <p class="text-gray-600 text-sm font-medium mb-4">Silakan login atau daftar untuk mengirim komentar.</p>
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                                class="px-5 py-2 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all active:scale-95 shadow-sm"
                                style="background: var(--color-primary)">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="px-5 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all active:scale-95 shadow-sm">
                                Daftar
                            </a>
                        </div>
                    </div>
                @endauth

                {{-- Comment List --}}
                @if($article->comments->isEmpty())
                    <div class="text-center py-10 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 opacity-50"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <p class="text-sm font-medium">Belum ada komentar. Jadilah yang pertama!</p>
                    </div>
                @else
                    <div class="space-y-5">
                        @foreach($article->comments as $comment)
                            <div class="flex gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-sm font-black shrink-0">
                                    {{ mb_strtoupper(mb_substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div class="bg-white border border-gray-100 rounded-2xl px-4 py-3 shadow-sm">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-sm font-bold text-gray-900">{{ $comment->user->name }}</span>
                                            <span class="text-[10px] text-gray-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $comment->body }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

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

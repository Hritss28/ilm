@extends('layouts.app')

@section('content')
<div class="container-custom py-10">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Main Content --}}
        <div class="flex-1">
            {{-- Category Header --}}
            <div class="flex items-center gap-4 mb-8">
                <div class="flex-1 flex flex-col gap-[2px]">
                    <div class="h-[3px] bg-red-600 w-full"></div>
                    <div class="h-[3px] bg-red-600 w-full"></div>
                </div>
                <h1 class="text-2xl font-black text-red-700 tracking-tighter px-4 whitespace-nowrap uppercase">
                    {{ strtoupper($category->name) }}
                </h1>
                <div class="flex-1 flex flex-col gap-[2px]">
                    <div class="h-[3px] bg-red-600 w-full"></div>
                    <div class="h-[3px] bg-red-600 w-full"></div>
                </div>
            </div>

            {{-- News List --}}
            <div class="space-y-2">
                @forelse($articles as $article)
                <a href="{{ route('news.show', $article->slug) }}" class="flex flex-col md:flex-row gap-6 mb-10 pb-10 border-b border-gray-100 last:border-0 group cursor-pointer">
                    <div class="w-full md:w-80 aspect-[4/2.8] flex-shrink-0 overflow-hidden bg-white border border-gray-100 shadow-sm rounded-sm">
                        @if($article->thumbnail)
                        <img loading="lazy" src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        @endif
                    </div>
                    <div class="flex-1 flex flex-col">
                        <h3 class="text-[22px] font-black text-gray-800 leading-[1.2] mb-3 group-hover:text-red-600 transition-colors tracking-tight">
                            {{ $article->title }}
                        </h3>
                        <div class="flex items-center gap-4 text-[11px] text-gray-400 mb-4 font-bold tracking-wider">
                            <span class="flex items-center gap-1.5 uppercase bg-gray-50 px-2 py-0.5 rounded italic">🕒 {{ $article->published_at?->translatedFormat('d M Y (H:i)') }}</span>
                            <span class="flex items-center gap-1.5 uppercase bg-gray-50 px-2 py-0.5 rounded italic">💬 {{ $article->views }}</span>
                            <span class="text-red-500 font-black hover:text-red-700 transition-colors uppercase border-b-2 border-red-100 hover:border-red-600">Selengkapnya</span>
                        </div>
                        <p class="text-[14px] text-gray-500 line-clamp-3 leading-relaxed font-medium">
                            {{ $article->excerpt }}
                        </p>
                    </div>
                </a>
                @empty
                <div class="text-center py-16 text-gray-400">
                    <p class="text-lg font-bold">Belum ada berita di kategori ini.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($articles->hasPages())
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="w-full lg:w-80">
            <x-sidebar />
        </div>
    </div>
</div>
@endsection

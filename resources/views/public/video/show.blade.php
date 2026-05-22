@extends('layouts.app')

@section('content')
<div class="container-custom py-8">
    <div class="flex flex-col lg:flex-row gap-10">
        <div class="flex-1">
            {{-- Video Title --}}
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ $video->title }}</h1>

            {{-- Metadata --}}
            <div class="flex items-center gap-4 text-[11px] text-gray-500 mb-6 font-medium">
                <span>{{ number_format($video->views) }} views</span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span>{{ $video->created_at->translatedFormat('l, j F Y | H:i') }} WIB</span>
            </div>

            {{-- Video Embed --}}
            <div class="relative w-full pb-[56.25%] bg-gray-900 rounded-sm overflow-hidden mb-6 group border border-gray-200">
                @if($video->display_thumbnail)
                    <a href="{{ $video->video_url }}" target="_blank" class="absolute inset-0 w-full h-full block group cursor-pointer">
                        <img src="{{ $video->display_thumbnail }}" alt="{{ $video->title }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center text-white shadow-lg">
                                <svg class="w-10 h-10 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-white flex-col gap-2">
                        <svg class="w-12 h-12 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <p class="text-gray-300">Thumbnail belum tersedia.</p>
                        <a href="{{ $video->video_url }}" target="_blank" class="mt-2 px-4 py-2 bg-red-600 text-white rounded-full text-sm hover:bg-red-700 transition-colors">Tonton Videonya Langsung</a>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            @if($video->description)
            <div class="bg-white border border-gray-100 p-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-2">Deskripsi</h3>
                <p class="text-gray-700 whitespace-pre-line text-sm">{{ $video->description }}</p>
            </div>
            @endif

            {{-- Latest Videos --}}
            @if($latestVideos->isNotEmpty())
            <section class="border-t border-gray-100 pt-8 mt-8">
                <div class="border-l-4 border-primary pl-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tighter">Video Terbaru</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($latestVideos as $latestVideo)
                    <a href="{{ route('video.show', $latestVideo->id) }}" class="flex flex-col group cursor-pointer">
                        <div class="w-full aspect-[4/3] overflow-hidden bg-black mb-3 relative">
                            @if($latestVideo->display_thumbnail)
                            <img loading="lazy" src="{{ $latestVideo->display_thumbnail }}" alt="{{ $latestVideo->title }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-transform duration-500">
                            @endif
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-10 h-10 bg-black/40 rounded-full flex items-center justify-center border-2 border-white/60 group-hover:bg-primary group-hover:border-primary transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="text-white ml-0.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-400 text-[12px] font-medium mb-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>{{ $latestVideo->created_at->translatedFormat('l, j F Y | H:i') }} WIB</span>
                        </div>
                        <h4 class="font-bold text-gray-900 group-hover:text-primary transition-colors leading-snug line-clamp-2 text-lg">{{ $latestVideo->title }}</h4>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="w-full lg:w-80">
            <x-sidebar />
        </div>
    </div>
</div>
@endsection

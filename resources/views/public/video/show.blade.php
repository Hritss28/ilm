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
            <div class="relative w-full pb-[56.25%] bg-black rounded-sm overflow-hidden mb-6">
                @php
                    $embedUrl = '';
                    $videoUrl = $video->video_url;
                    if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                        $embedUrl = "https://www.youtube.com/embed/{$matches[1]}";
                    } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches)) {
                        $embedUrl = "https://player.vimeo.com/video/{$matches[1]}";
                    }
                @endphp

                @if($embedUrl)
                    <iframe src="{{ $embedUrl }}" class="absolute inset-0 w-full h-full" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen title="{{ $video->title }}"></iframe>
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-white">
                        <p>Video tidak dapat ditampilkan. <a href="{{ $videoUrl }}" target="_blank" class="underline">Buka di tab baru</a></p>
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
                            @if($latestVideo->thumbnail)
                            <img loading="lazy" src="{{ Storage::url($latestVideo->thumbnail) }}" alt="{{ $latestVideo->title }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-transform duration-500">
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

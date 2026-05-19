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

            {{-- Back Link --}}
            <div class="mt-6">
                <a href="{{ route('video.index') }}" class="text-primary hover:underline text-sm font-bold">&larr; Kembali ke Video</a>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="w-full lg:w-80">
            <x-sidebar />
        </div>
    </div>
</div>
@endsection

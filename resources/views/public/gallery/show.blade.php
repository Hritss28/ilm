@extends('layouts.app')

@section('content')
<div class="container-custom py-8" x-data="{ lightbox: false, currentImage: '', currentCaption: '', currentIndex: 0 }">
    {{-- Gallery Header --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $gallery->title }}</h1>
        @if($gallery->description)
            <p class="text-gray-600 mt-2">{{ $gallery->description }}</p>
        @endif
        <div class="flex items-center gap-4 text-sm text-gray-500 mt-3">
            <span>{{ $gallery->images->count() }} foto</span>
            <span>&bull;</span>
            <span>{{ $gallery->created_at->format('d M Y') }}</span>
            @if($gallery->creator)
                <span>&bull;</span>
                <span>Oleh: {{ $gallery->creator->name }}</span>
            @endif
        </div>
    </div>

    {{-- Image Grid --}}
    @if($gallery->images->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($gallery->images as $index => $image)
                <div class="cursor-pointer group" @click="lightbox = true; currentImage = '{{ Storage::url($image->image_url) }}'; currentCaption = '{{ addslashes($image->caption ?? '') }}'; currentIndex = {{ $index }}">
                    <img src="{{ Storage::url($image->image_url) }}" alt="{{ $image->caption ?? $gallery->title }}"
                         class="w-full h-40 md:h-48 object-cover rounded-lg group-hover:opacity-90 transition" loading="lazy">
                    @if($image->caption)
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $image->caption }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg p-8 text-center">
            <p class="text-gray-500">Galeri ini belum memiliki foto.</p>
        </div>
    @endif

    {{-- Lightbox --}}
    <div x-show="lightbox" x-transition.opacity class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" @keydown.escape.window="lightbox = false">
        {{-- Close button --}}
        <button @click="lightbox = false" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        {{-- Previous --}}
        <button @click="currentIndex = (currentIndex - 1 + {{ $gallery->images->count() }}) % {{ $gallery->images->count() }}; currentImage = [{{ $gallery->images->map(fn($img) => "'" . Storage::url($img->image_url) . "'")->implode(',') }}][currentIndex]; currentCaption = [{{ $gallery->images->map(fn($img) => "'" . addslashes($img->caption ?? '') . "'")->implode(',') }}][currentIndex]"
                class="absolute left-4 text-white hover:text-gray-300 z-10">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        {{-- Image --}}
        <div class="max-w-5xl max-h-[85vh] flex flex-col items-center">
            <img loading="lazy" :src="currentImage" :alt="currentCaption" class="max-w-full max-h-[75vh] object-contain rounded-lg">
            <p x-show="currentCaption" x-text="currentCaption" class="text-white text-sm mt-3 text-center"></p>
        </div>

        {{-- Next --}}
        <button @click="currentIndex = (currentIndex + 1) % {{ $gallery->images->count() }}; currentImage = [{{ $gallery->images->map(fn($img) => "'" . Storage::url($img->image_url) . "'")->implode(',') }}][currentIndex]; currentCaption = [{{ $gallery->images->map(fn($img) => "'" . addslashes($img->caption ?? '') . "'")->implode(',') }}][currentIndex]"
                class="absolute right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>

    {{-- Back Link --}}
    <div class="mt-6">
        <a href="{{ route('gallery.index') }}" class="text-primary hover:underline text-sm font-medium">&larr; Kembali ke Potret</a>
    </div>
</div>
@endsection

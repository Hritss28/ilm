@extends('layouts.app')

@section('content')
<div class="container-custom py-8" x-data="{ 
    lightbox: false, 
    currentIndex: 0, 
    images: [{{ $gallery->images->map(fn($img) => "'" . Storage::url($img->image_url) . "'")->implode(',') }}],
    captions: [{{ $gallery->images->map(fn($img) => "'" . addslashes($img->caption ?? '') . "'")->implode(',') }}],
    get currentImage() { return this.images[this.currentIndex]; },
    get currentCaption() { return this.captions[this.currentIndex]; }
}">
    <div class="flex flex-col lg:flex-row gap-10">
        {{-- Gallery Content --}}
        <article class="flex-1">
            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">{{ $gallery->title }}</h1>

            {{-- Metadata --}}
            <div class="flex flex-wrap items-center gap-4 text-[11px] text-gray-500 mb-6 pb-4 border-b border-gray-100">
                <span class="flex items-center gap-1 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $gallery->creator->name ?? 'Administrator' }}
                </span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span class="font-medium">
                    {{ $gallery->created_at->translatedFormat('l, j F Y | H:i') }} WIB
                </span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span class="font-medium">{{ $gallery->images->count() }} foto</span>
            </div>

            {{-- Main Image Slider --}}
            @if($gallery->images->isNotEmpty())
                <div class="relative w-full aspect-[4/3] md:aspect-video bg-gray-100 overflow-hidden mb-8 group shadow-sm">
                    <img :src="currentImage" :alt="currentCaption || '{{ addslashes($gallery->title) }}'" class="w-full h-full object-contain bg-black cursor-pointer transition-opacity duration-300" @click="lightbox = true">
                    
                    <div x-show="currentCaption" class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent p-6 pt-16">
                        <p x-text="currentCaption" class="text-white text-sm md:text-lg font-medium"></p>
                    </div>

                    @if($gallery->images->count() > 1)
                        {{-- Previous Button --}}
                        <button @click="currentIndex = (currentIndex - 1 + images.length) % images.length" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-black/50 hover:bg-primary text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-md focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        
                        {{-- Next Button --}}
                        <button @click="currentIndex = (currentIndex + 1) % images.length" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-black/50 hover:bg-primary text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-md focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Photo Counter Badge --}}
                        <div class="absolute top-4 right-4 bg-black/60 text-white text-xs font-bold px-3 py-1.5 rounded-sm backdrop-blur-sm">
                            <span x-text="currentIndex + 1"></span> / {{ $gallery->images->count() }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- Description --}}
            @if($gallery->description)
                <div class="prose prose-lg max-w-none mb-8 text-gray-700">
                    {!! nl2br(e($gallery->description)) !!}
                </div>
            @endif

            {{-- Potret Terkini --}}
            @if(isset($latestGalleries) && $latestGalleries->isNotEmpty())
            <section class="border-t border-gray-100 pt-8">
                <div class="border-l-4 border-primary pl-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tighter">Potret Terkini</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($latestGalleries as $related)
                    <a href="{{ route('gallery.show', $related->slug) }}" class="flex flex-col group cursor-pointer">
                        <div class="w-full aspect-[4/3] overflow-hidden bg-gray-100 mb-3">
                            @if($related->cover_image)
                            <img loading="lazy" src="{{ Storage::url($related->cover_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-400 text-[12px] font-medium mb-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>{{ $related->created_at->translatedFormat('l, j F Y | H:i') }} WIB</span>
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

    {{-- Lightbox --}}
    <div x-show="lightbox" style="display: none;" x-transition.opacity class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4 md:p-8" @keydown.escape.window="lightbox = false">
        {{-- Close button --}}
        <button @click="lightbox = false" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white transition-colors z-50 focus:outline-none">
            <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        @if($gallery->images->count() > 1)
            {{-- Previous --}}
            <button @click.stop="currentIndex = (currentIndex - 1 + images.length) % images.length" class="absolute left-2 md:left-8 text-white/50 hover:text-white transition-colors z-50 focus:outline-none">
                <svg class="w-10 h-10 md:w-14 md:h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            
            {{-- Next --}}
            <button @click.stop="currentIndex = (currentIndex + 1) % images.length" class="absolute right-2 md:right-8 text-white/50 hover:text-white transition-colors z-50 focus:outline-none">
                <svg class="w-10 h-10 md:w-14 md:h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        @endif

        {{-- Image & Caption Container --}}
        <div class="relative max-w-6xl w-full h-full flex flex-col items-center justify-center" @click.stop>
            <div class="relative w-full flex-1 flex items-center justify-center min-h-0">
                <img loading="lazy" :src="currentImage" :alt="currentCaption" class="max-w-full max-h-full object-contain rounded-md shadow-2xl" @click="lightbox = false">
            </div>
            <div class="mt-6 text-center max-w-3xl shrink-0">
                <p x-show="currentCaption" x-text="currentCaption" class="text-white text-sm md:text-base opacity-90"></p>
                <div class="text-white/50 text-xs mt-2 font-medium">
                    <span x-text="currentIndex + 1"></span> dari {{ $gallery->images->count() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

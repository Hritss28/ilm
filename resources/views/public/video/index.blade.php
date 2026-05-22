@extends('layouts.app')

@section('content')
<div class="container-custom py-8">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row items-center justify-between border-b-2 border-gray-100 pb-6 mb-10 gap-6">
        <div class="flex items-center gap-4 border-l-4 border-primary pl-4">
            <h1 class="text-3xl font-black text-primary uppercase tracking-tighter">VIDEO</h1>
        </div>

        <form action="{{ route('video.index') }}" method="GET" class="relative flex-1 md:max-w-md w-full">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari video..." class="w-full bg-white border border-gray-200 px-4 py-2.5 rounded-sm text-sm outline-none focus:border-primary transition-colors pr-10 shadow-sm">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2">
            {{-- Grid Section --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-12">
                @forelse($videos as $video)
                <a href="{{ route('video.show', $video->id) }}" class="flex flex-col group cursor-pointer">
                    <div class="relative aspect-video overflow-hidden rounded-sm mb-4 bg-black">
                        @if($video->display_thumbnail)
                        <img loading="lazy" src="{{ $video->display_thumbnail }}" alt="{{ $video->title }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500" loading="lazy">
                        @endif
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-black/40 rounded-full flex items-center justify-center border-2 border-white/60 group-hover:bg-primary group-hover:border-primary transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="text-white ml-1"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-gray-400 text-[10px] font-medium mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>{{ $video->created_at->translatedFormat('l, j F Y') }} | {{ $video->created_at->format('H:i') }} WIB</span>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 leading-snug group-hover:text-primary transition-colors line-clamp-2">
                        {{ $video->title }}
                    </h3>
                </a>
                @empty
                <div class="col-span-2 text-center py-16 text-gray-400">
                    <p class="text-lg font-bold">Belum ada video.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($videos->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $videos->links() }}
            </div>
            @endif
        </div>

        <div class="w-full lg:w-80">
            <x-sidebar />
        </div>
    </div>
</div>
@endsection

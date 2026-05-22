@extends('layouts.app')

@section('content')
<div class="container-custom py-8">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row items-center justify-between border-b-2 border-gray-100 pb-6 mb-10 gap-6">
        <div class="flex items-center gap-4 border-l-4 border-primary pl-4">
            <h1 class="text-3xl font-black text-primary uppercase tracking-tighter">POTRET KELANA KOTA</h1>
        </div>

        <form action="{{ route('gallery.index') }}" method="GET" class="relative flex-1 md:max-w-md w-full">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari potret kelana kota..." class="w-full bg-white border border-gray-200 px-4 py-2.5 rounded-sm text-sm outline-none focus:border-primary transition-colors pr-10 shadow-sm">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2">
            {{-- Grid Section --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-12">
                @forelse($galleries as $gallery)
                <a href="{{ route('gallery.show', $gallery->slug) }}" class="flex flex-col group cursor-pointer">
                    <div class="aspect-[3/2] overflow-hidden rounded-sm mb-4 bg-gray-100">
                        @if($gallery->cover_image)
                        <img loading="lazy" src="{{ Storage::url($gallery->cover_image) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5 text-gray-400 text-[10px] font-medium mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>{{ $gallery->created_at->translatedFormat('l, j F Y') }} | {{ $gallery->created_at->format('H:i') }} WIB</span>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 leading-snug group-hover:text-primary transition-colors line-clamp-2">
                        {{ $gallery->title }}
                    </h3>
                </a>
                @empty
                <div class="col-span-2 text-center py-16 text-gray-400">
                    <p class="text-lg font-bold">Belum ada galeri foto.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($galleries->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $galleries->links() }}
            </div>
            @endif
        </div>

        <div class="w-full lg:w-80">
            <x-sidebar />
        </div>
    </div>
</div>
@endsection

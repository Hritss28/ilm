@extends('layouts.app')

@section('content')
<div class="container-custom py-8">
    <div class="flex flex-col lg:flex-row gap-10">
        {{-- Main Content --}}
        <div class="flex-1">
            {{-- Page Header / Filter Section --}}
            <div class="flex flex-col md:flex-row items-center justify-between border-b-2 border-gray-100 pb-6 mb-10 gap-6">
                <div class="flex items-center gap-4 border-l-4 border-primary pl-4">
                    <h1 class="text-3xl font-black text-primary uppercase tracking-tighter">NEWS</h1>
                </div>

                <form action="{{ route('news.search') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-500 mr-2">Lihat Berita Tanggal:</span>
                    <div class="flex items-center gap-2">
                        <select name="day" class="bg-white border border-gray-200 text-gray-700 text-xs px-3 py-2 rounded outline-none w-16 focus:border-primary">
                            <option value="">--</option>
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ request('day') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <select name="month" class="bg-white border border-gray-200 text-gray-700 text-xs px-3 py-2 rounded outline-none w-24 focus:border-primary">
                            <option value="">--</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $bulan)
                                <option value="{{ $idx + 1 }}" {{ request('month') == ($idx + 1) ? 'selected' : '' }}>{{ $bulan }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="bg-white border border-gray-200 text-gray-700 text-xs px-3 py-2 rounded outline-none w-24 focus:border-primary">
                            <option value="">--</option>
                            @for($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <button type="submit" class="bg-red-600 text-white px-8 py-2 rounded-sm text-xs font-bold hover:bg-red-700 transition-colors shadow-sm">
                        CARI
                    </button>
                </form>
            </div>

            {{-- Search bar --}}
            <form action="{{ route('news.search') }}" method="GET" class="mb-8">
                <div class="flex gap-2">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berita berdasarkan kata kunci..." class="flex-1 bg-white border border-gray-200 text-gray-700 text-sm px-4 py-2.5 rounded outline-none focus:border-primary transition-colors">
                    <button type="submit" class="bg-red-600 text-white px-6 py-2.5 rounded text-xs font-bold hover:bg-red-700 transition-colors shadow-sm">
                        CARI
                    </button>
                </div>
            </form>

            @if(request('q'))
            <p class="text-sm text-gray-500 mb-8">Hasil pencarian untuk: <strong class="text-gray-900">"{{ request('q') }}"</strong> ({{ $articles->total() }} hasil)</p>
            @elseif(request('day') || request('month') || request('year'))
            <p class="text-sm text-gray-500 mb-8">Berita tanggal: <strong class="text-gray-900">{{ request('day', '--') }}/{{ request('month', '--') }}/{{ request('year', '--') }}</strong> ({{ $articles->total() }} hasil)</p>
            @endif

            {{-- News List --}}
            <div class="space-y-12">
                @forelse($articles as $article)
                <a href="{{ route('news.show', $article->slug) }}" class="flex gap-8 group cursor-pointer border-b border-gray-100 pb-10 last:border-0 block">
                    <div class="w-1/3 aspect-[4/3] overflow-hidden rounded flex-shrink-0">
                        @if($article->thumbnail)
                        <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest">{{ $article->category->name ?? '' }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 leading-tight group-hover:text-primary transition-colors mb-3 line-clamp-2">
                            {{ $article->title }}
                        </h3>
                        <p class="text-[11px] text-gray-500 font-medium">
                            {{ $article->published_at?->translatedFormat('l, j F Y | H:i') }} WIB
                        </p>
                    </div>
                </a>
                @empty
                <div class="text-center py-16 text-gray-400">
                    <p class="text-lg font-bold">Tidak ada berita ditemukan.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($articles->hasPages())
            <div class="mt-12 flex justify-center">
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

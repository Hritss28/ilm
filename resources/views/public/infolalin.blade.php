@extends('layouts.app')

@section('content')
<div class="container-custom py-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-center justify-between border-b-2 border-gray-100 pb-6 mb-6 gap-6">
        <div class="flex items-center gap-4 border-l-4 border-primary pl-4">
            <h1 class="text-3xl font-black text-primary uppercase tracking-tighter">INFO LALIN</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start" x-data="{ activeTab: 'today' }">

        {{-- Left Content: Timeline --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Filter Tabs --}}
            <div class="flex gap-2 border-b border-gray-100 pb-0 overflow-x-auto no-scrollbar">
                <button @click="activeTab = 'today'" :class="activeTab === 'today' ? 'text-primary after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-1 after:bg-primary' : 'text-gray-400 hover:text-navy-900'" class="px-6 py-4 text-[11px] font-black uppercase tracking-widest transition-all relative">
                    Hari ini
                </button>
                <button @click="activeTab = 'yesterday'" :class="activeTab === 'yesterday' ? 'text-primary after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-1 after:bg-primary' : 'text-gray-400 hover:text-navy-900'" class="px-6 py-4 text-[11px] font-black uppercase tracking-widest transition-all relative">
                    Kemarin
                </button>
                <button @click="activeTab = 'week'" :class="activeTab === 'week' ? 'text-primary after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-1 after:bg-primary' : 'text-gray-400 hover:text-navy-900'" class="px-6 py-4 text-[11px] font-black uppercase tracking-widest transition-all relative">
                    Semua (7 hari)
                </button>
            </div>

            {{-- Today --}}
            <div x-show="activeTab === 'today'" x-transition class="space-y-14">
                @if($todayNews->count() > 0)
                <div class="space-y-10">
                    <div class="flex items-center gap-4">
                        <h2 class="text-lg font-black text-navy-900 uppercase tracking-tight">{{ now()->translatedFormat('d F Y - l') }}</h2>
                        <div class="h-[1px] flex-1 bg-gray-100"></div>
                    </div>

                    <div class="space-y-0 relative">
                        <div class="absolute left-[38px] top-6 bottom-0 w-[2px] bg-gray-100"></div>

                        @foreach($todayNews as $item)
                        <div class="flex gap-8 relative pb-12 last:pb-0">
                            <div class="w-20 pt-4 text-center">
                                <div class="text-xl font-black text-navy-900 font-mono tracking-tighter">{{ $item->published_at->format('H:i') }}</div>
                                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                    @if($item->published_at->format('H') < 12) Pagi @elseif($item->published_at->format('H') < 15) Siang @elseif($item->published_at->format('H') < 18) Sore @else Malam @endif
                                </div>
                            </div>
                            <div class="relative z-10 w-4 h-4 mt-6 -ml-10 bg-white border-4 border-primary rounded-full shadow-sm"></div>
                            <div class="flex-1 bg-white rounded-2xl p-7 border border-gray-100 transition-all group shadow-sm hover:shadow-md">
                                <div class="mb-4">
                                    <span class="text-[9px] font-black px-3 py-1.5 rounded border uppercase tracking-wider bg-red-50 text-red-700 border-red-100">
                                        LALU LINTAS
                                    </span>
                                </div>
                                <a href="{{ route('news.show', $item->slug) }}">
                                    <h3 class="text-xl font-bold mb-3 text-navy-900 group-hover:text-primary transition-colors">
                                        {{ $item->title }}
                                    </h3>
                                </a>
                                <p class="text-sm text-gray-500 leading-relaxed max-w-2xl mb-6 font-medium">
                                    {{ strip_tags($item->content) }}
                                </p>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span class="text-[11px] font-bold uppercase tracking-wide">{{ $item->getAttributes()['excerpt'] ?: 'Mojokerto' }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center py-16 text-gray-400">
                    <p class="text-lg font-bold">Belum ada info lalin hari ini.</p>
                </div>
                @endif
            </div>

            {{-- Yesterday --}}
            <div x-show="activeTab === 'yesterday'" x-transition class="space-y-14">
                @if($yesterdayNews->count() > 0)
                <div class="space-y-10">
                    <div class="flex items-center gap-4">
                        <h2 class="text-lg font-black text-navy-900 uppercase tracking-tight">{{ now()->subDay()->translatedFormat('d F Y - l') }}</h2>
                        <div class="h-[1px] flex-1 bg-gray-100"></div>
                    </div>

                    <div class="space-y-0 relative">
                        <div class="absolute left-[38px] top-6 bottom-0 w-[2px] bg-gray-100"></div>

                        @foreach($yesterdayNews as $item)
                        <div class="flex gap-8 relative pb-12 last:pb-0">
                            <div class="w-20 pt-4 text-center">
                                <div class="text-xl font-black text-navy-900 font-mono tracking-tighter">{{ $item->published_at->format('H:i') }}</div>
                                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                    @if($item->published_at->format('H') < 12) Pagi @elseif($item->published_at->format('H') < 15) Siang @elseif($item->published_at->format('H') < 18) Sore @else Malam @endif
                                </div>
                            </div>
                            <div class="relative z-10 w-4 h-4 mt-6 -ml-10 bg-white border-4 border-primary rounded-full shadow-sm"></div>
                            <div class="flex-1 bg-white rounded-2xl p-7 border border-gray-100 transition-all group shadow-sm hover:shadow-md">
                                <div class="mb-4">
                                    <span class="text-[9px] font-black px-3 py-1.5 rounded border uppercase tracking-wider bg-red-50 text-red-700 border-red-100">
                                        LALU LINTAS
                                    </span>
                                </div>
                                <a href="{{ route('news.show', $item->slug) }}">
                                    <h3 class="text-xl font-bold mb-3 text-navy-900 group-hover:text-primary transition-colors">
                                        {{ $item->title }}
                                    </h3>
                                </a>
                                <p class="text-sm text-gray-500 leading-relaxed max-w-2xl mb-6 font-medium">
                                    {{ strip_tags($item->content) }}
                                </p>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span class="text-[11px] font-bold uppercase tracking-wide">{{ $item->getAttributes()['excerpt'] ?: 'Mojokerto' }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center py-16 text-gray-400">
                    <p class="text-lg font-bold">Tidak ada info lalin kemarin.</p>
                </div>
                @endif
            </div>

            {{-- Week --}}
            <div x-show="activeTab === 'week'" x-transition class="space-y-14">
                @if($weekNews->count() > 0)
                    @php
                        $grouped = $weekNews->groupBy(fn($item) => $item->published_at->translatedFormat('d F Y - l'));
                    @endphp
                    @foreach($grouped as $date => $items)
                    <div class="space-y-10">
                        <div class="flex items-center gap-4">
                            <h2 class="text-lg font-black text-navy-900 uppercase tracking-tight">{{ $date }}</h2>
                            <div class="h-[1px] flex-1 bg-gray-100"></div>
                        </div>

                        <div class="space-y-0 relative">
                            <div class="absolute left-[38px] top-6 bottom-0 w-[2px] bg-gray-100"></div>

                            @foreach($items as $item)
                            <div class="flex gap-8 relative pb-12 last:pb-0">
                                <div class="w-20 pt-4 text-center">
                                    <div class="text-xl font-black text-navy-900 font-mono tracking-tighter">{{ $item->published_at->format('H:i') }}</div>
                                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                        @if($item->published_at->format('H') < 12) Pagi @elseif($item->published_at->format('H') < 15) Siang @elseif($item->published_at->format('H') < 18) Sore @else Malam @endif
                                    </div>
                                </div>
                                <div class="relative z-10 w-4 h-4 mt-6 -ml-10 bg-white border-4 border-primary rounded-full shadow-sm"></div>
                                <div class="flex-1 bg-white rounded-2xl p-7 border border-gray-100 transition-all group shadow-sm hover:shadow-md">
                                    <div class="mb-4">
                                        <span class="text-[9px] font-black px-3 py-1.5 rounded border uppercase tracking-wider bg-red-50 text-red-700 border-red-100">
                                            LALU LINTAS
                                        </span>
                                    </div>
                                    <a href="{{ route('news.show', $item->slug) }}">
                                        <h3 class="text-xl font-bold mb-3 text-navy-900 group-hover:text-primary transition-colors">
                                            {{ $item->title }}
                                        </h3>
                                    </a>
                                    <p class="text-sm text-gray-500 leading-relaxed max-w-2xl mb-6 font-medium">
                                        {{ strip_tags($item->content) }}
                                    </p>
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span class="text-[11px] font-bold uppercase tracking-wide">{{ $item->getAttributes()['excerpt'] ?: 'Mojokerto' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="text-center py-16 text-gray-400">
                    <p class="text-lg font-bold">Tidak ada info lalin dalam 7 hari terakhir.</p>
                </div>
                @endif
            </div>

        </div>

        {{-- Right Sidebar --}}
        <div class="lg:sticky lg:top-28">
            <x-sidebar />
        </div>

    </div>
</div>
@endsection

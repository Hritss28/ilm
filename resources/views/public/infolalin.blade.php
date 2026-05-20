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
                    <div class="mb-6">
                        <h2 class="text-xl font-black text-navy-900 uppercase tracking-tighter">{{ now()->translatedFormat('d F Y - l') }}</h2>
                    </div>

                    <div class="space-y-0 relative">
                        <div class="absolute left-[38px] top-6 bottom-0 w-[2px] bg-gray-100"></div>

                        @foreach($todayNews as $item)
                            <x-infolalin-card :item="$item" />
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
                    <div class="mb-6">
                        <h2 class="text-xl font-black text-navy-900 uppercase tracking-tighter">{{ now()->subDay()->translatedFormat('d F Y - l') }}</h2>
                    </div>

                    <div class="space-y-0 relative">
                        <div class="absolute left-[38px] top-6 bottom-0 w-[2px] bg-gray-100"></div>

                        @foreach($yesterdayNews as $item)
                            <x-infolalin-card :item="$item" />
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
                        $grouped = $weekNews->groupBy(fn($item) => $item->incident_date->translatedFormat('d F Y - l'));
                    @endphp
                    @foreach($grouped as $date => $items)
                    <div class="space-y-10">
                        <div class="mb-6">
                            <h2 class="text-xl font-black text-navy-900 uppercase tracking-tighter">{{ $date }}</h2>
                        </div>

                        <div class="space-y-0 relative">
                            <div class="absolute left-[38px] top-6 bottom-0 w-[2px] bg-gray-100"></div>

                            @foreach($items as $item)
                                <x-infolalin-card :item="$item" />
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

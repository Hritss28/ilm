@props(['item'])

@php
    $statusActive = ($item->status === 'Masih aktif' || empty($item->status));
    $dotClass = $statusActive ? 'border-red-500' : 'border-green-500';
    $badgeBg = $statusActive ? 'bg-red-50' : 'bg-green-50';
    $badgeText = $statusActive ? 'text-red-700' : 'text-green-700';
    $badgeBorder = $statusActive ? 'border-red-100' : 'border-green-100';
@endphp

<div class="flex gap-8 relative pb-12 last:pb-0">
    <div class="w-20 pt-4 text-center">
        <div class="text-xl font-black text-navy-900 font-mono tracking-tighter">{{ $item->start_time->format('H:i') }}</div>
        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
            @if($item->start_time->format('H') < 12) Pagi 
            @elseif($item->start_time->format('H') < 15) Siang 
            @elseif($item->start_time->format('H') < 18) Sore 
            @else Malam @endif
        </div>
    </div>
    
    <div class="relative z-10 w-4 h-4 mt-6 -ml-10 bg-white border-4 {{ $dotClass }} rounded-full shadow-sm"></div>
    
    <div class="flex-1 bg-white rounded-2xl p-7 border border-gray-100 transition-all group shadow-sm hover:shadow-md">
        <div class="mb-4 flex flex-wrap gap-2 items-center">
            <span class="text-[9px] font-black px-3 py-1.5 rounded border uppercase tracking-wider bg-gray-100 text-gray-700 border-gray-200">
                {{ $item->category ?? 'LALU LINTAS' }}
            </span>
            <span class="text-[9px] font-black px-3 py-1.5 rounded border uppercase tracking-wider {{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }}">
                {{ $item->status ?? 'Masih aktif' }}
            </span>
        </div>
        
        <h3 class="text-xl font-bold mb-3 text-navy-900">
            {{ $item->title }}
        </h3>
        
        <p class="text-sm text-gray-500 leading-relaxed max-w-2xl mb-6 font-medium">
            {{ Str::limit(strip_tags($item->description), 150) }}
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6">
            <div class="flex items-center gap-2 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span class="text-[11px] font-bold tracking-wide">{{ $item->location }}</span>
            </div>
            
            @if($item->estimated_end_time)
            <div class="flex items-center gap-2 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span class="text-[11px] font-bold tracking-wide">Estimasi Selesai: {{ $item->estimated_end_time->format('H:i') }}</span>
            </div>
            @endif
            
            @if($item->source)
            <div class="flex items-center gap-2 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span class="text-[11px] font-bold tracking-wide">Sumber: {{ $item->source }}</span>
            </div>
            @endif
            
            @if($item->alternative_route)
            <div class="flex items-start gap-2 text-gray-500 md:col-span-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 shrink-0"><path d="m9 18 6-6-6-6"/></svg>
                <span class="text-[11px] font-bold tracking-wide leading-relaxed">Rute Alternatif: {{ $item->alternative_route }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

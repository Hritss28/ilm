@php
    $breakingNews = app(\App\Services\CacheService::class)->getBreakingNews();
@endphp

@if($breakingNews->count() > 0)
<div class="bg-gray-100 border-b border-gray-200 py-3">
    <div class="container-custom flex items-center gap-4">
        <div class="bg-red-600 text-white px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-sm whitespace-nowrap">
            BREAKING NEWS
        </div>
        <div class="flex items-center gap-2 overflow-hidden flex-1" x-data="{ current: 0, items: {{ $breakingNews->count() }} }">
            @foreach($breakingNews as $index => $news)
                <a href="{{ route('news.show', $news->slug) }}" 
                   x-show="current === {{ $index }}" 
                   x-transition
                   class="text-[13px] text-gray-800 font-medium truncate hover:text-primary cursor-pointer">
                    {{ $news->title }}
                </a>
            @endforeach
            <div class="flex gap-2 ml-auto">
                <button @click="current = current > 0 ? current - 1 : items - 1" class="cursor-pointer text-gray-400 hover:text-gray-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <button @click="current = current < items - 1 ? current + 1 : 0" class="cursor-pointer text-gray-400 hover:text-gray-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

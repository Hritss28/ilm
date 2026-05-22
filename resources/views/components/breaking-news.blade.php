@php
    $breakingNews = app(\App\Services\CacheService::class)->getBreakingNews();
@endphp

@if($breakingNews->count() > 0)
<div class="bg-gray-100 border-b border-gray-200 py-3">
    <div class="container-custom flex items-center gap-4">
        <div class="bg-red-600 text-white px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-sm whitespace-nowrap">
            BREAKING NEWS
        </div>
        <div class="flex items-center gap-2 overflow-hidden flex-1" 
             x-data="{ 
                 current: 0, 
                 items: {{ $breakingNews->count() }},
                 next() { this.current = this.current < this.items - 1 ? this.current + 1 : 0 },
                 prev() { this.current = this.current > 0 ? this.current - 1 : this.items - 1 }
             }"
             x-init="setInterval(() => { next() }, 5000)">
            
            <div class="flex-1 relative h-6 overflow-hidden flex items-center">
                @foreach($breakingNews as $index => $news)
                    <a href="{{ route('news.show', $news->slug) }}" 
                       x-show="current === {{ $index }}" 
                       x-transition:enter="transition ease-out duration-300 absolute w-full"
                       x-transition:enter-start="opacity-0 translate-x-12"
                       x-transition:enter-end="opacity-100 translate-x-0"
                       x-transition:leave="transition ease-in duration-300 absolute w-full"
                       x-transition:leave-start="opacity-100 translate-x-0"
                       x-transition:leave-end="opacity-0 -translate-x-12"
                       class="text-[13px] text-gray-800 font-medium truncate hover:text-primary cursor-pointer block w-full">
                        {{ $news->title }}
                    </a>
                @endforeach
            </div>
            
            <div class="flex gap-2 ml-auto shrink-0 z-10 bg-gray-100">
                <button @click="prev()" type="button" class="cursor-pointer text-gray-400 hover:text-gray-900 transition-colors p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <button @click="next()" type="button" class="cursor-pointer text-gray-400 hover:text-gray-900 transition-colors p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@props(['paginator'])

@if($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="flex items-center justify-center space-x-1 mt-8">
    {{-- Previous --}}
    @if($paginator->onFirstPage())
        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">&laquo; Prev</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-secondary bg-white border border-gray-300 rounded-md hover:bg-primary hover:text-white hover:border-primary transition">&laquo; Prev</a>
    @endif

    {{-- Page Numbers --}}
    @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
        @if($page == $paginator->currentPage())
            <span class="px-3 py-2 text-sm text-white bg-primary rounded-md font-semibold">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="px-3 py-2 text-sm text-secondary bg-white border border-gray-300 rounded-md hover:bg-primary hover:text-white hover:border-primary transition">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Next --}}
    @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-secondary bg-white border border-gray-300 rounded-md hover:bg-primary hover:text-white hover:border-primary transition">Next &raquo;</a>
    @else
        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next &raquo;</span>
    @endif
</nav>
@endif

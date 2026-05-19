@extends('layouts.admin')

@section('title', 'Kelola Gallery')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Gallery</h1>
    <a href="{{ route('admin.galleries.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Gallery
    </a>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
    {{ session('success') }}
</div>
@endif

<!-- Gallery Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($galleries as $gallery)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Cover Image -->
        <div class="aspect-video bg-gray-100 relative">
            @if($gallery->cover_image)
                <img loading="lazy" src="{{ Storage::url($gallery->cover_image) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
            <!-- Image count badge -->
            <div class="absolute top-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $gallery->images->count() }} foto
            </div>
            <!-- Status badge -->
            @unless($gallery->is_active)
            <div class="absolute top-2 left-2 bg-gray-600 text-white text-xs px-2 py-1 rounded">
                Nonaktif
            </div>
            @endunless
        </div>

        <!-- Info -->
        <div class="p-4">
            <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $gallery->title }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ $gallery->creator->name ?? '-' }} &middot; {{ $gallery->created_at->format('d/m/Y') }}</p>

            <!-- Actions -->
            <div class="mt-3 flex items-center gap-2">
                <a href="{{ route('admin.galleries.edit', $gallery) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded hover:bg-blue-100">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus gallery ini beserta semua gambarnya?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded hover:bg-red-100">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 text-gray-500">
        Belum ada gallery. <a href="{{ route('admin.galleries.create') }}" class="text-red-600 hover:underline">Buat gallery pertama</a>.
    </div>
    @endforelse
</div>

@if($galleries->hasPages())
<div class="mt-6">
    {{ $galleries->links() }}
</div>
@endif
@endsection

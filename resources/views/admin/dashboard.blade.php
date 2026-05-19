@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
{{-- Page Header --}}
<div class="mb-10">
    <div class="flex items-center gap-4 mb-2">
        <div class="h-0.5 w-12 bg-red-600"></div>
        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">
            Selamat Datang di Halaman Administrator
        </h1>
    </div>
    <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
        <span>Dashboard</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        <span class="text-red-500">Home</span>
    </div>
</div>

{{-- Quick Actions Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="{{ route('admin.news.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all group cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-red-50 text-red-600 rounded-lg group-hover:bg-red-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <span class="text-[10px] font-black text-red-600 uppercase border-b border-red-100 hover:border-red-600 transition-colors">Kelola</span>
        </div>
        <div class="space-y-1">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Total</h3>
            <p class="text-lg font-black text-gray-800">{{ $totalNews }} Berita</p>
        </div>
    </a>

    <a href="{{ route('admin.users.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all group cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-red-50 text-red-600 rounded-lg group-hover:bg-red-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="text-[10px] font-black text-red-600 uppercase border-b border-red-100 hover:border-red-600 transition-colors">Kelola</span>
        </div>
        <div class="space-y-1">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Modul Pengguna</h3>
            <p class="text-lg font-black text-gray-800">{{ $totalUsers }} Pengguna</p>
        </div>
    </a>

    <a href="{{ route('admin.categories.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all group cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-red-50 text-red-600 rounded-lg group-hover:bg-red-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
            </div>
            <span class="text-[10px] font-black text-red-600 uppercase border-b border-red-100 hover:border-red-600 transition-colors">Kelola</span>
        </div>
        <div class="space-y-1">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Kategori</h3>
            <p class="text-lg font-black text-gray-800">{{ $totalCategories }} Kategori</p>
        </div>
    </a>

    <a href="{{ route('admin.videos.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all group cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-red-50 text-red-600 rounded-lg group-hover:bg-red-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/><line x1="7" y1="2" x2="7" y2="22"/><line x1="17" y1="2" x2="17" y2="22"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
            </div>
            <span class="text-[10px] font-black text-red-600 uppercase border-b border-red-100 hover:border-red-600 transition-colors">Kelola</span>
        </div>
        <div class="space-y-1">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Modul Video</h3>
            <p class="text-lg font-black text-gray-800">{{ $totalVideos }} Video</p>
        </div>
    </a>

    <a href="{{ route('admin.galleries.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all group cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-red-50 text-red-600 rounded-lg group-hover:bg-red-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <span class="text-[10px] font-black text-red-600 uppercase border-b border-red-100 hover:border-red-600 transition-colors">Kelola</span>
        </div>
        <div class="space-y-1">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Modul Galeri</h3>
            <p class="text-lg font-black text-gray-800">{{ $totalGalleries }} Galeri</p>
        </div>
    </a>

    <a href="{{ route('admin.advertisements.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all group cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-red-50 text-red-600 rounded-lg group-hover:bg-red-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
            </div>
            <span class="text-[10px] font-black text-red-600 uppercase border-b border-red-100 hover:border-red-600 transition-colors">Kelola</span>
        </div>
        <div class="space-y-1">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Modul Iklan</h3>
            <p class="text-lg font-black text-gray-800">{{ $totalAds }} Iklan</p>
        </div>
    </a>
</div>

{{-- Quick Info Bar --}}
<div class="mt-12 bg-white p-6 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-red-600 flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="flex items-center gap-4 text-center md:text-left">
        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        </div>
        <div>
            <h4 class="text-sm font-black text-gray-800 uppercase">Modul Administrator</h4>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Pantau dan kelola seluruh konten web Anda dari satu tempat</p>
        </div>
    </div>
    <a href="{{ route('home') }}" target="_blank" class="bg-gray-900 text-white px-6 py-3 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-neutral-800 transition-all flex items-center gap-2 shadow-lg active:scale-95">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        Kunjungi Situs
    </a>
</div>

{{-- Recent Articles --}}
<div class="mt-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Artikel Terbaru</h2>
        <a href="{{ route('admin.news.index') }}" class="text-[10px] font-black text-red-600 uppercase tracking-widest hover:underline">Lihat Semua →</a>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">#</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Judul</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Views</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentNews as $index => $news)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.news.edit', $news->id) }}" class="text-xs font-bold text-gray-800 hover:text-red-600 transition-colors line-clamp-1">{{ $news->title }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] font-black text-gray-400 uppercase">{{ $news->category->name ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-[9px] font-black rounded-full uppercase tracking-widest border {{ $news->status === 'published' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-gray-50 text-gray-400 border-gray-100' }}">
                            {{ $news->status === 'published' ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-gray-500">{{ number_format($news->views) }}</td>
                    <td class="px-6 py-4 text-[10px] font-bold text-gray-400">{{ $news->published_at?->format('d M Y') ?? $news->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola Iklan')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Iklan</h1>
</div>

<div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex gap-3">
        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <h3 class="text-sm font-bold text-blue-800 mb-1">Informasi Penempatan Iklan</h3>
            <ul class="text-xs text-blue-700 space-y-1 list-disc list-inside">
                <li><strong>Hero Kanan:</strong> Tampil di sebelah kanan headline utama di halaman depan (Maksimal 2 slot).</li>
                <li><strong>Banner Horizontal:</strong> Tampil memanjang di bawah headline utama (Maksimal 1 slot).</li>
                <li><strong>Sidebar Kanan:</strong> Tampil di sidebar kanan semua halaman (Maksimal 3 slot, 1 slot bawah akan lengket/sticky).</li>
                <li><strong>Footer:</strong> Tampil memanjang di bagian bawah sebelum footer (Maksimal 1 slot).</li>
            </ul>
        </div>
    </div>
</div>

<!-- Advertisements Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($advertisements as $ad)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img loading="lazy" src="{{ Storage::url($ad->image_url) }}" alt="{{ $ad->title }}" class="w-20 h-14 object-cover rounded border border-gray-200">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $ad->title }}</div>
                        <a href="{{ $ad->link_url }}" target="_blank" class="text-xs text-blue-500 hover:underline truncate block max-w-xs">{{ Str::limit($ad->link_url, 40) }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($ad->position === 'top') bg-purple-100 text-purple-800
                            @elseif($ad->position === 'sidebar') bg-blue-100 text-blue-800
                            @elseif($ad->position === 'content') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($ad->position === 'top') Hero Kanan
                            @elseif($ad->position === 'sidebar') Sidebar Kanan
                            @elseif($ad->position === 'content') Banner Horizontal
                            @elseif($ad->position === 'footer') Footer
                            @else {{ ucfirst($ad->position) }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $ad->priority }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($ad->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                        @if($ad->starts_at || $ad->ends_at)
                            {{ $ad->starts_at ? $ad->starts_at->format('d/m/Y') : '-' }}
                            s/d
                            {{ $ad->ends_at ? $ad->ends_at->format('d/m/Y') : '-' }}
                        @else
                            <span class="text-gray-400">Tanpa batas</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.advertisements.edit', $ad) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        Belum ada iklan di database.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($advertisements->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $advertisements->links() }}
    </div>
    @endif
</div>
@endsection

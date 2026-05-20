@extends('layouts.admin')

@section('title', 'Data Info Lalin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row items-center justify-between mb-10 gap-6">
        <div class="text-left">
            <div class="flex items-center gap-4 mb-2">
                <div class="h-0.5 w-12 bg-red-600"></div>
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Data Info Lalin</h1>
            </div>
            <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-500 transition-colors uppercase">Modul Berita</a>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                <span class="text-red-500 uppercase">Daftar Laporan</span>
            </div>
        </div>

        <a href="{{ route('admin.info-lalin.create') }}" class="px-8 py-4 bg-red-700 text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-xl shadow-red-900/20 hover:bg-red-800 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Info Baru
        </a>
    </div>

    <form action="{{ route('admin.info-lalin.index') }}" method="GET" class="mb-6 p-6 bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status</label>
                <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all appearance-none">
                    <option value="">Semua Status</option>
                    <option value="Masih aktif" {{ request('status') === 'Masih aktif' ? 'selected' : '' }}>Masih aktif</option>
                    <option value="Sudah selesai" {{ request('status') === 'Sudah selesai' ? 'selected' : '' }}>Sudah selesai</option>
                    <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all">
            </div>
            <div class="space-y-2">
                <button type="submit" class="w-full px-6 py-3 bg-gray-800 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-gray-900 transition-all active:scale-95">Filter</button>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-8 py-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-8 py-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    <th class="px-8 py-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($infoLalins as $item)
                <tr class="hover:bg-gray-50/30 transition-colors group">
                    <td class="px-8 py-6">
                        <span class="text-xs font-bold text-red-600 font-mono tracking-tight uppercase">{{ $item->start_time?->format('H:i') }} &bull; {{ $item->incident_date?->format('d M Y') }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-bold text-gray-800 group-hover:text-red-700 transition-colors">{{ $item->title }}</span>
                            <span class="text-xs font-medium text-gray-400">{{ $item->author?->name ?? 'Administrator' }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <span class="inline-block px-4 py-2 rounded-lg bg-gray-50 text-xs font-bold {{ $item->status === 'Masih aktif' ? 'text-red-600' : 'text-gray-500' }} uppercase border border-gray-100">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-end gap-2 text-[10px] font-bold">
                            <a href="{{ route('admin.info-lalin.edit', $item->id) }}" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="{{ route('admin.info-lalin.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus info lalin ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-gray-900 hover:text-white border border-gray-100 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="opacity-20"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <p class="text-sm font-bold uppercase tracking-wider text-gray-400 mt-2">Belum ada data info lalin</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($infoLalins->hasPages())
        <div class="p-6 border-t border-gray-100">
            {{ $infoLalins->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

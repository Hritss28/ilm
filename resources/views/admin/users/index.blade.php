@extends('layouts.admin')

@section('title', 'Data Redaktur')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-4 mb-2">
        <div class="w-8 h-[2px] bg-red-600"></div>
        <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight">DATA REDAKTUR</h1>
    </div>
    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
        <span>Modul Redaktur</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        <span class="text-red-600">DATA PENGGUNA</span>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Top Filters -->
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-4">
            <!-- Retain search if existing -->
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">FILTER KECAMATAN :</label>
            <select name="kecamatan" onchange="this.form.submit()" class="text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-md pl-3 pr-8 py-1.5 focus:outline-none focus:border-gray-300">
                <option value="" {{ request('kecamatan') === null ? 'selected' : '' }}>Semua Kecamatan</option>
                <optgroup label="KABUPATEN">
                    @foreach(['Bangsal', 'Dawarblandong', 'Dlanggu', 'Gedeg', 'Gondang', 'Jatirejo', 'Jetis', 'Kemlagi', 'Kutorejo', 'Mojoanyar', 'Mojosari', 'Ngoro', 'Pacet', 'Pungging', 'Puri', 'Sooko', 'Trawas', 'Trowulan'] as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="KOTA">
                    @foreach(['Magersari', 'Kranggan', 'Prajurit Kulon'] as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </optgroup>
            </select>

            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">FILTER STATUS :</label>
            <select name="status" onchange="this.form.submit()" class="text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-md pl-3 pr-8 py-1.5 focus:outline-none focus:border-gray-300">
                <option value="" {{ request('status') === null ? 'selected' : '' }}>Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </form>

        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-4">
            <!-- Retain status if existing -->
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('kecamatan'))
                <input type="hidden" name="kecamatan" value="{{ request('kecamatan') }}">
            @endif
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">PENCARIAN :</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari redaktur..." class="text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-md pl-3 pr-8 py-1.5 focus:outline-none focus:border-gray-300 w-48 placeholder-gray-300">
                <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-12 text-center">#</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">EMAIL/USERNAME</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">NAMA PENGGUNA</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">TELP</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">KECAMATAN</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">STATUS</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">TOTAL POST</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">TOTAL PENYIMAK</th>
                    @if(auth()->user()->isAdmin())
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">AKSI</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-xs font-bold text-gray-400 text-center">
                        {{ $users->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-blue-500">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-gray-700">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-gray-700 text-center">
                        {{ $user->telp ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-gray-700 text-center">
                        {{ $user->kecamatan ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->is_active)
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-green-100 text-green-600">AKTIF</span>
                        @else
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-red-100 text-red-500">TIDAK AKTIF</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-gray-700 text-center">
                        {{ $user->news_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-gray-700 text-center">
                        0
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-500 hover:text-blue-700" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? '8' : '7' }}" class="px-6 py-12 text-center text-xs font-bold text-gray-400">
                        Tidak ada data redaktur ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t border-gray-100 flex items-center justify-between">
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            MENAMPILKAN {{ $users->firstItem() ?? 0 }} S/D {{ $users->lastItem() ?? 0 }} DARI {{ $users->total() }} DATA
        </div>
        @if($users->hasPages())
        <div class="flex gap-1">
            @if ($users->onFirstPage())
                <span class="px-3 py-1.5 border border-gray-100 rounded text-[10px] font-black text-gray-300 uppercase tracking-widest bg-gray-50">&larr; PREV</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1.5 border border-gray-200 rounded text-[10px] font-black text-gray-500 hover:text-gray-900 uppercase tracking-widest hover:border-gray-300">&larr; PREV</a>
            @endif

            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1.5 bg-blue-600 text-white rounded text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 shadow-sm shadow-blue-500/30">NEXT &rarr;</a>
            @else
                <span class="px-3 py-1.5 bg-gray-100 text-gray-400 rounded text-[10px] font-black uppercase tracking-widest">NEXT &rarr;</span>
            @endif
        </div>
        @endif
    </div>
</div>

@if(auth()->user()->isAdmin())
<div class="mt-6 flex justify-end">
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-[11px] font-black uppercase tracking-widest rounded-md hover:bg-red-700 shadow-sm shadow-red-500/30 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Redaktur
    </a>
</div>
@endif
@endsection

@extends('layouts.admin')

@section('title', 'Tema')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-0.5 w-12 bg-red-600"></div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Tema Website</h1>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-500 transition-colors">Home</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span class="text-red-500">Modul Web &bull; Tema</span>
        </div>
        <p class="mt-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-16">Digunakan sebagai warna dasar halaman utama web</p>
    </div>

    @php
        $themes = [
            ['name' => 'Merah', 'color' => 'bg-red-600', 'active' => true],
            ['name' => 'Kuning', 'color' => 'bg-yellow-500', 'active' => false],
            ['name' => 'Biru', 'color' => 'bg-blue-600', 'active' => false],
            ['name' => 'Ungu', 'color' => 'bg-purple-600', 'active' => false],
            ['name' => 'Hijau', 'color' => 'bg-green-600', 'active' => false],
        ];
    @endphp

    <div class="space-y-6">
        @foreach($themes as $theme)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 flex flex-col md:flex-row items-center justify-between gap-6 transition-all hover:shadow-md group">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl shadow-inner group-hover:scale-105 transition-transform {{ $theme['color'] }}"></div>
                <div>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Warna Dasar {{ $theme['name'] }}</h3>
                    <div class="flex items-center gap-3 mt-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-2">Pasang:</p>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="theme_{{ $loop->index }}" value="yes" {{ $theme['active'] ? 'checked' : '' }} class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Iya</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="theme_{{ $loop->index }}" value="no" {{ !$theme['active'] ? 'checked' : '' }} class="w-4 h-4 text-gray-600 border-gray-300 focus:ring-gray-500">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Tidak</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="px-8 py-2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-neutral-800 transition-all shadow-lg active:scale-95">Simpan</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

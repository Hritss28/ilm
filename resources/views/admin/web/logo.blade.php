@extends('layouts.admin')

@section('title', 'Logo')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-0.5 w-12 bg-red-600"></div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Logo Website</h1>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-500 transition-colors">Home</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span class="text-red-500">Modul Web &bull; Logo</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            <div class="flex items-center gap-8">
                <div class="w-32 h-32 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center overflow-hidden">
                    <img loading="lazy" src="{{ asset('LogoBaruILM.png') }}" alt="Logo" class="w-full h-full object-contain p-2">
                </div>
                <div class="flex-1 space-y-4">
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Upload Logo Baru</h3>
                    <input type="file" accept="image/*" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-all">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Format: PNG, SVG. Rekomendasi: 200x200px (Maks. 500KB)</p>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button class="px-12 py-3 bg-red-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-red-900/40 hover:bg-red-800 transition-all active:scale-95">
                    Simpan Logo
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

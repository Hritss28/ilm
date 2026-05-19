@extends('layouts.admin')

@section('title', 'Modul Kata Jorok')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-0.5 w-12 bg-red-600"></div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">
                Modul Kata Jorok
            </h1>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-500 transition-colors">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span class="text-red-500">Kata Jorok</span>
        </div>
        <p class="mt-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-16">
            Filter kata-kata tidak pantas yang akan otomatis disensor pada komentar
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-8 space-y-6">
            <div class="space-y-4">
                <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest">Daftar Kata Terlarang</h2>
                <p class="text-xs text-gray-500">Masukkan kata-kata yang ingin disensor, pisahkan dengan baris baru.</p>
                <textarea class="w-full px-6 py-5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-mono text-gray-800 h-[300px] focus:outline-none focus:ring-2 focus:ring-red-100 transition-all" placeholder="Masukkan kata per baris..."></textarea>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button class="px-12 py-3 bg-red-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-red-900/40 hover:bg-red-800 transition-all active:scale-95 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan Daftar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

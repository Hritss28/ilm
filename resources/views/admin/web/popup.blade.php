@extends('layouts.admin')

@section('title', 'Popup')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-0.5 w-12 bg-red-600"></div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Popup Website</h1>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-500 transition-colors">Home</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span class="text-red-500">Modul Web &bull; Popup</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            <div class="space-y-4">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Gambar Popup</h3>
                <p class="text-xs text-gray-500">Upload gambar yang akan ditampilkan sebagai popup saat pengunjung pertama kali membuka website.</p>
                <input type="file" accept="image/*" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-all">
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Link Tujuan</h3>
                <input type="url" placeholder="https://example.com" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all">
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Status</h3>
                <div class="flex items-center gap-8">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="popup_status" value="active" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                        <span class="text-xs font-black text-gray-500 uppercase tracking-widest">Aktif</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="popup_status" value="inactive" checked class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                        <span class="text-xs font-black text-gray-500 uppercase tracking-widest">Tidak Aktif</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button class="px-12 py-3 bg-red-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-red-900/40 hover:bg-red-800 transition-all active:scale-95">
                    Simpan Popup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

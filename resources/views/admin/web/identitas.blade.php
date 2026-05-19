@extends('layouts.admin')

@section('title', 'Identitas Web')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-0.5 w-12 bg-red-600"></div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Identitas Web</h1>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-500 transition-colors">Home</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span class="text-red-500">Identitas</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            <div class="space-y-4">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Website</label>
                <input type="text" value="{{ config('news_portal.site.name', 'Info Lantas Mojokerto') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all">
            </div>

            <div class="space-y-4">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Deskripsi Website</label>
                <textarea class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 h-24 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all resize-none">{{ config('news_portal.site.description', '') }}</textarea>
            </div>

            <div class="space-y-4">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Email Redaksi</label>
                <input type="email" value="{{ config('news_portal.site.contact_email', 'redaksi@infolantasmojokerto.com') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all">
            </div>

            <div class="space-y-4">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Telepon</label>
                <input type="text" value="{{ config('news_portal.site.contact_phone', '+62-321-123456') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Facebook</label>
                    <input type="url" value="https://www.facebook.com/InfoLantasMojokerto" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Instagram</label>
                    <input type="url" value="https://www.instagram.com/infolantasmojokerto" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">YouTube</label>
                    <input type="url" value="https://www.youtube.com/@InfoLantasMojokerto" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">TikTok</label>
                    <input type="url" value="https://www.tiktok.com/@info.lantas.mojokerto" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button class="px-12 py-3 bg-red-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-red-900/40 hover:bg-red-800 transition-all active:scale-95">
                    Simpan Identitas
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

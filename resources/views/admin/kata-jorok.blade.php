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
            Filter kata-kata tidak pantas yang akan otomatis disensor pada komentar berita
        </p>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl text-xs font-bold flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Word Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden sticky top-6">
                <div class="p-6">
                    <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-1">Tambah Kata</h2>
                    <p class="text-xs text-gray-500 mb-5">Kata akan otomatis disimpan dalam huruf kecil.</p>
                    <form action="{{ route('admin.kata-jorok.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <input type="text" name="word" id="word" value="{{ old('word') }}" required
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm font-mono font-medium rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-100 transition-all"
                                placeholder="contoh: kata_jorok">
                            @error('word')
                                <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-gray-900 text-white text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-800 transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Kata
                        </button>
                    </form>
                </div>
                <div class="px-6 pb-6">
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <p class="text-[11px] font-bold text-amber-700 uppercase tracking-widest mb-1">Cara Kerja</p>
                        <p class="text-xs text-amber-600 leading-relaxed">
                            Ketika pengguna berkomentar, setiap kata yang ada dalam daftar ini akan otomatis disensor. Contoh: <strong>BANGSAT → B*ng**t</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Word List --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest">Daftar Kata Terlarang</h2>
                    <span class="text-[11px] font-black text-gray-400 bg-gray-100 px-3 py-1 rounded-full">{{ $badWords->count() }} kata</span>
                </div>

                @if($badWords->isEmpty())
                    <div class="p-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-300"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                        <p class="text-sm font-bold text-gray-500 mb-1">Daftar kata jorok masih kosong</p>
                        <p class="text-xs text-gray-400">Tambahkan kata pertama menggunakan form di sebelah kiri.</p>
                    </div>
                @else
                    <div class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($badWords as $badWord)
                                <div class="flex items-center gap-1.5 bg-red-50 border border-red-100 rounded-lg px-3 py-1.5 group">
                                    <span class="text-sm font-mono font-bold text-red-800">{{ $badWord->word }}</span>
                                    <form action="{{ route('admin.kata-jorok.destroy', $badWord->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus kata \'{{ $badWord->word }}\' dari daftar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-300 hover:text-red-600 transition-colors ml-1" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

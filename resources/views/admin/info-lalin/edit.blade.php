@extends('layouts.admin')

@section('title', 'Edit Info Lalin')

@section('content')
<div class="max-w-4xl mx-auto pb-20">
    <div class="mb-10 text-left">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-0.5 w-12 bg-red-600"></div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Edit Info Lalin</h1>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
            <a href="{{ route('admin.info-lalin.index') }}" class="hover:text-red-500 transition-colors uppercase underline decoration-red-500/30 underline-offset-4">Kembali ke Daftar</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span class="text-red-500 uppercase">Edit Data</span>
        </div>
    </div>

    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @php $lalinCategory = \App\Models\Category::where('slug', 'lalu-lintas')->first(); @endphp
        <input type="hidden" name="category_id" value="{{ $lalinCategory?->id }}">
        <input type="hidden" name="status" value="{{ $news->status }}">

        <div class="bg-white rounded-2xl border border-gray-200 shadow-xl shadow-gray-200/50 overflow-hidden text-left">
            <div class="p-10 space-y-10">

                {{-- Pilih Kategori Kejadian --}}
                <div class="space-y-5">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1 h-4 bg-red-600 rounded-full inline-block mr-2"></span>
                        Pilih Kategori Kejadian
                    </label>
                    @php $currentType = $news->seo_keywords ?? 'KEMACETAN RAWAN'; @endphp
                    <div class="flex flex-wrap gap-3" x-data="{ selected: '{{ $currentType }}', custom: {{ !in_array($currentType, ['KEMACETAN RAWAN','PEMBATASAN','RUTE ALTERNATIF','UJI EMISI','LAINNYA']) ? 'true' : 'false' }} }">
                        @foreach(['KEMACETAN RAWAN', 'PEMBATASAN', 'RUTE ALTERNATIF', 'UJI EMISI'] as $type)
                        <label class="cursor-pointer" @click="selected = '{{ $type }}'; custom = false">
                            <input type="radio" name="lalin_type" value="{{ $type }}" class="sr-only" x-bind:checked="selected === '{{ $type }}'">
                            <div :class="selected === '{{ $type }}' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400'" class="px-6 py-3 rounded-full border text-[11px] font-black uppercase tracking-widest transition-all">
                                {{ $type }}
                            </div>
                        </label>
                        @endforeach
                        <label class="cursor-pointer" @click="selected = 'LAINNYA'; custom = true; $nextTick(() => $refs.customInput.focus())">
                            <input type="radio" name="lalin_type" value="LAINNYA" class="sr-only" x-bind:checked="selected === 'LAINNYA' || custom">
                            <div :class="(selected === 'LAINNYA' || custom) ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400'" class="px-6 py-3 rounded-full border text-[11px] font-black uppercase tracking-widest transition-all">
                                LAINNYA
                            </div>
                        </label>

                        <div x-show="custom" x-transition class="w-full mt-3">
                            <input type="text" name="lalin_type_custom" x-ref="customInput" placeholder="Ketik kategori kejadian manual..." class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ !in_array($currentType, ['KEMACETAN RAWAN','PEMBATASAN','RUTE ALTERNATIF','UJI EMISI','LAINNYA']) ? $currentType : '' }}">
                        </div>
                    </div>
                </div>

                {{-- Waktu & Tanggal --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Waktu Kejadian <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="time" name="waktu_kejadian" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ $news->published_at?->format('H:i') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Tanggal Kejadian <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kejadian" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ $news->published_at?->format('Y-m-d') }}">
                    </div>
                </div>

                {{-- Separator --}}
                <div class="flex items-center gap-4">
                    <div class="flex-1 h-[1px] bg-gray-100"></div>
                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-[0.3em]">Detail Konten</span>
                    <div class="flex-1 h-[1px] bg-gray-100"></div>
                </div>

                {{-- Judul --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Judul Informasi <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Jembatan Suramadu mengalami kemacetan" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('title', $news->title) }}">
                    @error('title')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="content" required rows="6" placeholder="Jelaskan kondisi lalin secara singkat dan jelas..." class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all resize-none">{{ old('content', $news->content) }}</textarea>
                    @error('content')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lokasi & Sumber --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Lokasi / Ruas Jalan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <input type="text" name="excerpt" required placeholder="Contoh: Jembatan Suramadu" class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('excerpt', $news->getRawOriginal('excerpt')) }}">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Sumber Informasi / Instansi</label>
                        <input type="text" name="source" placeholder="Contoh: Dishub Surabaya" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('source', $news->seo_description) }}">
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-10 py-6 bg-gray-50 border-t border-gray-100 flex items-center justify-center gap-4">
                <button type="submit" class="px-12 py-4 bg-red-700 text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-800 transition-all active:scale-95">
                    UPDATE INFO
                </button>
                <a href="{{ route('admin.info-lalin.index') }}" class="px-8 py-4 text-[11px] font-black uppercase text-gray-400 hover:text-gray-600 tracking-widest border border-gray-200 rounded-xl hover:border-gray-300 transition-all">
                    BATAL
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

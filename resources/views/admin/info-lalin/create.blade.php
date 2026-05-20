@extends('layouts.admin')

@section('title', 'Tambah Info Lalin')

@section('content')
<div class="max-w-4xl mx-auto pb-20">
    <div class="mb-10 text-left">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-0.5 w-12 bg-red-600"></div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Buat Info Lalin</h1>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
            <a href="{{ route('admin.info-lalin.index') }}" class="hover:text-red-500 transition-colors uppercase underline decoration-red-500/30 underline-offset-4">Kembali ke Daftar</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span class="text-red-500 uppercase">Input Baru</span>
        </div>
    </div>

    <form action="{{ route('admin.info-lalin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200 shadow-xl shadow-gray-200/50 overflow-hidden text-left">
            <div class="p-10 space-y-10">

                {{-- Pilih Kategori Kejadian --}}
                <div class="space-y-5">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1 h-4 bg-red-600 rounded-full inline-block mr-2"></span>
                        KATEGORI KEJADIAN
                    </label>
                    <div class="flex flex-wrap gap-3" x-data="{ selected: 'Kemacetan', custom: false }">
                        @foreach(['Kemacetan', 'Penutupan jalan', 'Rute alternatif', 'Kecelakaan', 'Uji emisi'] as $cat)
                        <label class="cursor-pointer" @click="selected = '{{ $cat }}'; custom = false">
                            <input type="radio" name="lalin_category" value="{{ $cat }}" class="sr-only" x-bind:checked="selected === '{{ $cat }}'">
                            <div :class="selected === '{{ $cat }}' ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400'" class="px-6 py-2.5 rounded-full border text-xs font-bold transition-all">
                                {{ $cat }}
                            </div>
                        </label>
                        @endforeach
                        <label class="cursor-pointer" @click="selected = 'Lainnya'; custom = true; $nextTick(() => $refs.customInput.focus())">
                            <input type="radio" name="lalin_category" value="Lainnya" class="sr-only" x-bind:checked="selected === 'Lainnya'">
                            <div :class="selected === 'Lainnya' ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400'" class="px-6 py-2.5 rounded-full border text-xs font-bold transition-all">
                                Lainnya
                            </div>
                        </label>

                        {{-- Custom input --}}
                        <div x-show="custom" x-transition class="w-full mt-3">
                            <input type="text" name="lalin_category_custom" x-ref="customInput" placeholder="Ketik kategori kejadian manual..." class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('lalin_category_custom') }}">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Waktu & Status --}}
                <div class="space-y-5">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1 h-4 bg-red-600 rounded-full inline-block mr-2"></span>
                        WAKTU & STATUS
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Tanggal kejadian <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_kejadian" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('tanggal_kejadian', now()->format('Y-m-d')) }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Waktu mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="waktu_kejadian" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('waktu_kejadian', now()->format('H:i')) }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Perkiraan selesai</label>
                            <input type="time" name="lalin_estimated_end" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('lalin_estimated_end') }}">
                            <p class="text-[10px] text-gray-400">Kosongkan jika belum diketahui</p>
                        </div>
                        <div class="space-y-2" x-data="{ status: 'Masih aktif' }">
                            <label class="text-xs font-bold text-gray-700">Status</label>
                            <div class="flex gap-3 h-11 items-center">
                                <label class="cursor-pointer" @click="status = 'Masih aktif'">
                                    <input type="radio" name="lalin_status" value="Masih aktif" class="sr-only" x-bind:checked="status === 'Masih aktif'" checked>
                                    <div :class="status === 'Masih aktif' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-white text-gray-500 border-gray-200'" class="px-5 py-2 rounded-full border text-xs font-bold transition-all">
                                        Masih aktif
                                    </div>
                                </label>
                                <label class="cursor-pointer" @click="status = 'Sudah selesai'">
                                    <input type="radio" name="lalin_status" value="Sudah selesai" class="sr-only" x-bind:checked="status === 'Sudah selesai'">
                                    <div :class="status === 'Sudah selesai' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-white text-gray-500 border-gray-200'" class="px-5 py-2 rounded-full border text-xs font-bold transition-all">
                                        Sudah selesai
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Detail Informasi --}}
                <div class="space-y-5">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1 h-4 bg-red-600 rounded-full inline-block mr-2"></span>
                        DETAIL INFORMASI
                    </label>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Judul singkat <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required maxlength="60" placeholder="Cth: Penutupan jalan Bypass Mojokerto" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('title') }}">
                            <p class="text-[10px] text-gray-400">Maksimal 60 karakter</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Deskripsi lengkap <span class="text-red-500">*</span></label>
                            <textarea name="content" required maxlength="200" rows="4" placeholder="Jelaskan kondisi, penyebab, dan dampaknya secara singkat..." class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all resize-none">{{ old('content') }}</textarea>
                            <p class="text-[10px] text-gray-400">Maksimal 200 karakter</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Rute alternatif yang disarankan</label>
                            <input type="text" name="lalin_alternative_route" maxlength="255" placeholder="Cth: Lewat Jl. Ahmad Yani - Jl. Pahlawan" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('lalin_alternative_route') }}">
                            <p class="text-[10px] text-gray-400">Opsional — isi maksimal 1 rute jika ada yang bisa digunakan</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Lokasi & Sumber --}}
                <div class="space-y-5">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1 h-4 bg-red-600 rounded-full inline-block mr-2"></span>
                        LOKASI & SUMBER
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Lokasi / ruas jalan <span class="text-red-500">*</span></label>
                            <input type="text" name="lalin_location" required maxlength="50" placeholder="Cth: Bypass Mojokerto" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('lalin_location') }}">
                            <p class="text-[10px] text-gray-400">Maksimal 50 karakter</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Sumber / instansi <span class="text-red-500">*</span></label>
                            <input type="text" name="lalin_source" required maxlength="100" placeholder="Cth: Dishub Surabaya" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('lalin_source') }}">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-10 py-6 bg-gray-50 border-t border-gray-100 flex items-center gap-4">
                <button type="submit" name="action" value="publish" class="px-8 py-3.5 bg-gray-800 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-gray-900 transition-all active:scale-95">
                    Publikasikan info
                </button>
                <button type="submit" name="action" value="draft" class="px-8 py-3.5 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition-all active:scale-95">
                    Simpan draf
                </button>
                <a href="{{ route('admin.info-lalin.index') }}" class="px-8 py-3.5 text-xs font-bold text-gray-500 hover:text-gray-700 transition-all ml-auto">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

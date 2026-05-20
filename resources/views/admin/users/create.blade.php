@extends('layouts.admin')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-4xl mx-auto pb-20">
    <div class="mb-10 text-left">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-0.5 w-12 bg-red-600"></div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Tambah Redaktur Baru</h1>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
            <a href="{{ route('admin.users.index') }}" class="hover:text-red-500 transition-colors uppercase underline decoration-red-500/30 underline-offset-4">Kembali ke Daftar</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span class="text-red-500 uppercase">Input Baru</span>
        </div>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200 shadow-xl shadow-gray-200/50 overflow-hidden text-left">
            <div class="p-10 space-y-10">

                <div class="space-y-5">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1 h-4 bg-red-600 rounded-full inline-block mr-2"></span>
                        PROFIL PENGGUNA
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="Cth: Budi Santoso" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Nama Panggilan</label>
                            <input type="text" name="nickname" placeholder="Cth: Budi" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('nickname') }}">
                            @error('nickname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required placeholder="Cth: budi@ilm.com" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Nomor Telepon</label>
                            <input type="text" name="telp" placeholder="Cth: 08123456789" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('telp') }}">
                            @error('telp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Alamat</label>
                            <textarea name="address" rows="3" placeholder="Alamat lengkap..." class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all resize-none">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Kecamatan</label>
                            <select name="kecamatan" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all">
                                <option value="" selected>Pilih Kecamatan...</option>
                                <optgroup label="KABUPATEN">
                                    @foreach(['Bangsal', 'Dawarblandong', 'Dlanggu', 'Gedeg', 'Gondang', 'Jatirejo', 'Jetis', 'Kemlagi', 'Kutorejo', 'Mojoanyar', 'Mojosari', 'Ngoro', 'Pacet', 'Pungging', 'Puri', 'Sooko', 'Trawas', 'Trowulan'] as $kec)
                                        <option value="{{ $kec }}" {{ old('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="KOTA">
                                    @foreach(['Magersari', 'Kranggan', 'Prajurit Kulon'] as $kec)
                                        <option value="{{ $kec }}" {{ old('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('kecamatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div class="space-y-5">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1 h-4 bg-red-600 rounded-full inline-block mr-2"></span>
                        KEAMANAN & STATUS
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Status Akun</label>
                            <div class="flex gap-3 h-11 items-center" x-data="{ active: {{ old('is_active', true) ? 'true' : 'false' }} }">
                                <label class="cursor-pointer" @click="active = true">
                                    <input type="radio" name="is_active" value="1" class="sr-only" x-bind:checked="active">
                                    <div :class="active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-white text-gray-500 border-gray-200'" class="px-5 py-2 rounded-full border text-xs font-bold transition-all">
                                        Aktif
                                    </div>
                                </label>
                                <label class="cursor-pointer" @click="active = false">
                                    <input type="radio" name="is_active" value="0" class="sr-only" x-bind:checked="!active">
                                    <div :class="!active ? 'bg-red-50 text-red-700 border-red-200' : 'bg-white text-gray-500 border-gray-200'" class="px-5 py-2 rounded-full border text-xs font-bold transition-all">
                                        Tidak Aktif
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="px-10 py-6 bg-gray-50 border-t border-gray-100 flex items-center gap-4">
                <button type="submit" class="px-8 py-3.5 bg-gray-800 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-gray-900 transition-all active:scale-95">
                    Simpan Pengguna
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-8 py-3.5 text-xs font-bold text-gray-500 hover:text-gray-700 transition-all ml-auto">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

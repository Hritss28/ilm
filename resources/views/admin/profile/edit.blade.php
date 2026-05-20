@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto pb-20">
    <div class="mb-10 text-left">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-0.5 w-12 bg-red-600"></div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Profil Saya</h1>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase ml-16">
            <span class="text-red-500 uppercase">Pengaturan Profil & Keamanan</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg text-sm font-bold border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="bg-white rounded-2xl border border-gray-200 shadow-xl shadow-gray-200/50 overflow-hidden text-left mb-8">
            <div class="p-10 space-y-10">
                <div class="space-y-5">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1 h-4 bg-red-600 rounded-full inline-block mr-2"></span>
                        PROFIL PENGGUNA
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Nama Panggilan</label>
                            <input type="text" name="nickname" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('nickname', $user->nickname) }}">
                            @error('nickname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Nomor Telepon</label>
                            <input type="text" name="telp" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all" value="{{ old('telp', $user->telp) }}">
                            @error('telp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-10 py-6 bg-gray-50 border-t border-gray-100 flex items-center gap-4">
                <button type="submit" class="px-8 py-3.5 bg-gray-800 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-gray-900 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-8 py-3.5 text-xs font-bold text-gray-500 hover:text-gray-700 transition-all ml-auto">
                    Batal
                </a>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-xl shadow-gray-200/50 overflow-hidden text-left" x-data="{ openPassword: false }">
        <div class="p-10 space-y-10">
            <div class="space-y-5">
                <div class="flex items-center justify-between">
                    <label class="text-[11px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1 h-4 bg-red-600 rounded-full inline-block mr-2"></span>
                        KEAMANAN (PASSWORD)
                    </label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="!openPassword">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-700">Password Saat Ini</label>
                        <div class="flex items-center gap-3">
                            <input type="password" value="********" disabled class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-500 cursor-not-allowed">
                            <button type="button" @click="openPassword = true" class="px-4 py-3 bg-red-50 text-red-600 font-bold text-xs rounded-lg hover:bg-red-100 transition-colors whitespace-nowrap">
                                Ubah Password
                            </button>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.profile.password') }}" x-show="openPassword" x-transition>
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Password Lama <span class="text-red-500">*</span></label>
                            <input type="password" name="current_password" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="hidden md:block"></div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Password Baru <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-300 transition-all">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-red-600 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-red-700 transition-all active:scale-95">
                            Simpan Password
                        </button>
                        <button type="button" @click="openPassword = false" class="px-6 py-2.5 text-xs font-bold text-gray-500 hover:text-gray-700 transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

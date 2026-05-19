@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
        &larr; Kembali ke Daftar Pengguna
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Edit Pengguna: {{ $user->name }}</h2>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                    required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="telp" class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
                <input type="text" name="telp" id="telp" value="{{ old('telp', $user->telp) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                    placeholder="081xxx">
                @error('telp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                <select name="kecamatan" id="kecamatan"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    <option value="" disabled {{ empty($user->kecamatan) ? 'selected' : '' }}>Pilih Kecamatan...</option>
                    <optgroup label="KABUPATEN">
                        @foreach(['Bangsal', 'Dawarblandong', 'Dlanggu', 'Gedeg', 'Gondang', 'Jatirejo', 'Jetis', 'Kemlagi', 'Kutorejo', 'Mojoanyar', 'Mojosari', 'Ngoro', 'Pacet', 'Pungging', 'Puri', 'Sooko', 'Trawas', 'Trowulan'] as $kec)
                            <option value="{{ $kec }}" {{ old('kecamatan', $user->kecamatan) === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="KOTA">
                        @foreach(['Magersari', 'Kranggan', 'Prajurit Kulon'] as $kec)
                            <option value="{{ $kec }}" {{ old('kecamatan', $user->kecamatan) === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </optgroup>
                </select>
                @error('kecamatan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="role"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                    required>
                    <option value="author" {{ old('role', $user->role) === 'author' ? 'selected' : '' }}>Author</option>
                    <option value="redaktur" {{ old('role', $user->role) === 'redaktur' ? 'selected' : '' }}>Redaktur</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" id="password"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-700">Aktif</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                    Perbarui Pengguna
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

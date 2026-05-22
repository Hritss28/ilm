@extends('layouts.admin')

@section('title', 'Tambah Iklan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.advertisements.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
        &larr; Kembali ke Daftar Iklan
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Tambah Iklan Baru</h2>

        <form method="POST" action="{{ route('admin.advertisements.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Iklan</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                    required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Iklan</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp,image/gif"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                    required>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, WebP, GIF. Maksimal 5MB.</p>
            </div>

            <div class="mb-4">
                <label for="link_url" class="block text-sm font-medium text-gray-700 mb-1">URL Tujuan</label>
                <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}" placeholder="https://..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                    required>
                @error('link_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Posisi</label>
                    <select name="position" id="position"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                        required>
                        <option value="top" {{ old('position') === 'top' ? 'selected' : '' }}>Hero Kanan (2 slot)</option>
                        <option value="sidebar" {{ old('position') === 'sidebar' ? 'selected' : '' }}>Sidebar Kanan (3 slot)</option>
                        <option value="content" {{ old('position') === 'content' ? 'selected' : '' }}>Banner Horizontal (di bawah hero)</option>
                        <option value="footer" {{ old('position') === 'footer' ? 'selected' : '' }}>Footer (di atas footer)</option>
                    </select>
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                    <input type="number" name="priority" id="priority" value="{{ old('priority', 0) }}" min="0"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                        required>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Angka lebih besar = prioritas lebih tinggi.</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-700">Aktif</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">Mulai Tayang</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    @error('starts_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika langsung tayang.</p>
                </div>

                <div>
                    <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-1">Selesai Tayang</label>
                    <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    @error('ends_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tanpa batas waktu.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                    Simpan Iklan
                </button>
                <a href="{{ route('admin.advertisements.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

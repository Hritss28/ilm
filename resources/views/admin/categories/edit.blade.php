@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
        &larr; Kembali ke Daftar Kategori
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Edit Kategori: {{ $category->name }}</h2>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <input type="number" name="order" id="order" value="{{ old('order', $category->order) }}" min="0"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                @error('order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Angka lebih kecil ditampilkan lebih dulu.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                    Perbarui Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

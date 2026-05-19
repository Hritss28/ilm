@extends('layouts.admin')

@section('title', 'Edit ' . $page->title)

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.pages.index') }}" class="text-sm text-gray-600 hover:text-gray-800">&larr; Kembali ke Daftar Halaman</a>
    </div>

    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Edit Halaman: {{ $page->title }}</h2>

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Halaman <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug (read-only) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" value="{{ $page->slug }}" disabled
                    class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm text-gray-500">
                <p class="mt-1 text-xs text-gray-500">Slug tidak dapat diubah.</p>
            </div>

            <!-- Content with TinyMCE -->
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Konten <span class="text-red-500">*</span></label>
                <textarea name="content" id="content" rows="20" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 tinymce-editor">{{ old('content', $page->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Meta Info -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Terakhir diupdate:</span>
                    <span class="text-gray-800 ml-1">{{ $page->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Diupdate oleh:</span>
                    <span class="text-gray-800 ml-1">{{ $page->updater->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.pages.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.tinymce-editor',
        height: 500,
        plugins: 'lists link image table code wordcount fullscreen',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image table | code fullscreen',
        menubar: 'file edit view insert format tools table',
        branding: false,
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; line-height: 1.6; }',
    });
</script>
@endpush
@endsection

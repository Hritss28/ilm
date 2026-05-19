@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.news.index') }}" class="text-sm text-gray-600 hover:text-gray-800">&larr; Kembali ke Daftar Berita</a>
    </div>

    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Berita</h2>

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Konten <span class="text-red-500">*</span></label>
                <textarea name="content" id="content" rows="15" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 tinymce-editor">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Thumbnail -->
            <div class="mb-4">
                <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                    onchange="previewThumbnail(this)">
                <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, WebP. Maksimal 2MB.</p>
                @error('thumbnail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="thumbnail-preview" class="mt-2 hidden">
                    <img loading="lazy" id="thumbnail-img" src="" alt="Preview" class="w-48 h-32 object-cover rounded-lg">
                </div>
            </div>
        </div>

        <!-- Status & Options -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Status & Opsi</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured -->
                <div class="flex items-center pt-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Featured</span>
                    </label>
                </div>

                <!-- Breaking News -->
                <div class="flex items-center pt-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="is_breaking_news" value="0">
                        <input type="checkbox" name="is_breaking_news" value="1" {{ old('is_breaking_news') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Breaking News</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- SEO Fields -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">SEO</h2>

            <div class="mb-4">
                <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-1">SEO Title</label>
                <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title') }}" maxlength="255"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                <p class="mt-1 text-xs text-gray-500">Kosongkan untuk menggunakan judul berita.</p>
            </div>

            <div class="mb-4">
                <label for="seo_description" class="block text-sm font-medium text-gray-700 mb-1">SEO Description</label>
                <textarea name="seo_description" id="seo_description" rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('seo_description') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Kosongkan untuk auto-generate dari konten.</p>
            </div>

            <div class="mb-4">
                <label for="seo_keywords" class="block text-sm font-medium text-gray-700 mb-1">SEO Keywords</label>
                <input type="text" name="seo_keywords" id="seo_keywords" value="{{ old('seo_keywords') }}" maxlength="500"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                <p class="mt-1 text-xs text-gray-500">Pisahkan dengan koma.</p>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                Simpan Berita
            </button>
            <a href="{{ route('admin.news.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300">
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
        height: 400,
        plugins: 'lists link image table code wordcount',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
        menubar: false,
        branding: false,
    });

    function previewThumbnail(input) {
        const preview = document.getElementById('thumbnail-preview');
        const img = document.getElementById('thumbnail-img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection

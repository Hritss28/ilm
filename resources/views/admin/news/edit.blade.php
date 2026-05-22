@extends('layouts.admin')

@section('title', 'Edit Berita')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Postingan</h1>
    </div>

    <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Berita</h2>

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $news->title) }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-lg">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $news->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Konten <span class="text-red-500">*</span></label>
                        <textarea name="content" id="content" rows="18" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 tinymce-editor">{{ old('content', $news->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SEO Block -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Pengaturan SEO</h2>

                    <div class="mb-4">
                        <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-1">SEO Title</label>
                        <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $news->seo_title) }}" maxlength="255"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan untuk menggunakan judul berita.</p>
                    </div>

                    <div class="mb-4">
                        <label for="seo_description" class="block text-sm font-medium text-gray-700 mb-1">SEO Description</label>
                        <textarea name="seo_description" id="seo_description" rows="3"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('seo_description', $news->seo_description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Kosongkan untuk auto-generate dari konten.</p>
                    </div>

                    <div>
                        <label for="seo_keywords" class="block text-sm font-medium text-gray-700 mb-1">SEO Keywords</label>
                        <input type="text" name="seo_keywords" id="seo_keywords" value="{{ old('seo_keywords', $news->seo_keywords) }}" maxlength="500"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <p class="mt-1 text-xs text-gray-500">Pisahkan dengan koma.</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Publish Action -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Publikasi</h2>
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="hidden" {{ old('status', $news->status) == 'hidden' ? 'selected' : '' }}>Hidden</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full py-2.5 px-4 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Update Berita
                        </button>
                        <a href="{{ route('admin.news.index') }}" class="w-full py-2.5 px-4 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors text-center border border-gray-300">
                            Batal
                        </a>
                    </div>
                </div>

                <!-- Thumbnail -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Gambar Utama (Thumbnail)</h2>
                    
                    @if($news->thumbnail)
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 mb-2">Thumbnail saat ini:</p>
                            <img loading="lazy" src="{{ Storage::url($news->thumbnail) }}" alt="Current thumbnail" class="w-full aspect-video object-cover rounded-lg shadow-sm border border-gray-200">
                        </div>
                    @endif

                    <div>
                        <div class="mb-3 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative group hover:border-red-400 transition-colors cursor-pointer" onclick="document.getElementById('thumbnail').click()">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-red-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                        <span>Upload gambar baru</span>
                                        <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp" class="sr-only" onchange="previewThumbnail(this)">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah. Max 2MB</p>
                            </div>
                        </div>
                        @error('thumbnail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <div id="thumbnail-preview" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview Thumbnail Baru:</p>
                            <img loading="lazy" id="thumbnail-img" src="" alt="Preview" class="w-full aspect-video object-cover rounded-lg shadow-sm border border-gray-200">
                            <button type="button" onclick="removeThumbnail()" class="mt-2 text-xs text-red-600 hover:text-red-800 font-medium">Batal Ubah Gambar</button>
                        </div>
                    </div>
                </div>

            </div>
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

    function removeThumbnail() {
        const input = document.getElementById('thumbnail');
        const preview = document.getElementById('thumbnail-preview');
        const img = document.getElementById('thumbnail-img');
        
        input.value = '';
        img.src = '';
        preview.classList.add('hidden');
    }
</script>
@endpush
@endsection

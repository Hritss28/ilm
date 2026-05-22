@extends('layouts.admin')

@section('title', 'Tambah Video')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Video</h1>
    </div>

    <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Video</h2>

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-lg">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Video URL Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Sumber Video</h2>

                    <div class="mb-4">
                        <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">URL Video (YouTube, TikTok, Instagram, dll) <span class="text-red-500">*</span></label>
                        <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}" required
                            placeholder="https://www.youtube.com/watch?v=... atau link medsos lain"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            oninput="updateVideoPreview(this.value)">
                        <p class="mt-1 text-xs text-gray-500">Masukkan URL Video yang valid. Preview otomatis hanya tersedia untuk YouTube/Vimeo.</p>
                        @error('video_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Video Embed Preview -->
                    <div id="video-preview-container" class="mb-2 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preview</label>
                        <div class="aspect-video w-full rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                            <iframe id="video-preview-iframe" src="" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Publish Action -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Publikasi</h2>
                    
                    <div class="mb-6">
                        <label class="flex items-center cursor-pointer p-3 border rounded-md hover:bg-gray-50 transition-colors">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 w-5 h-5">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-700">Aktif</span>
                                <span class="block text-xs text-gray-500">Tampilkan di halaman publik</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full py-2.5 px-4 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Simpan Video
                        </button>
                        <a href="{{ route('admin.videos.index') }}" class="w-full py-2.5 px-4 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors text-center border border-gray-300">
                            Batal
                        </a>
                    </div>
                </div>

                <!-- Thumbnail -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Gambar Utama (Thumbnail)</h2>
                    
                    <div>
                        <div class="mb-3 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative group hover:border-red-400 transition-colors cursor-pointer bg-gray-50" onclick="document.getElementById('thumbnail').click()">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-red-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="thumbnail" class="relative cursor-pointer bg-transparent rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                        <span>Upload gambar</span>
                                        <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp" class="sr-only" onchange="previewThumbnail(this)">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">Max 2MB. Opsional.</p>
                            </div>
                        </div>
                        @error('thumbnail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <div id="thumbnail-preview" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                            <img loading="lazy" id="thumbnail-img" src="" alt="Preview" class="w-full aspect-video object-cover rounded-lg shadow-sm border border-gray-200">
                            <button type="button" onclick="removeThumbnail()" class="mt-2 text-xs text-red-600 hover:text-red-800 font-medium">Hapus Gambar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function parseVideoUrl(url) {
        if (!url) return null;

        // YouTube patterns
        let match = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
        if (match) {
            return { type: 'youtube', id: match[1], embedUrl: 'https://www.youtube.com/embed/' + match[1] };
        }

        // Vimeo patterns
        match = url.match(/vimeo\.com\/(\d+)/);
        if (match) {
            return { type: 'vimeo', id: match[1], embedUrl: 'https://player.vimeo.com/video/' + match[1] };
        }

        return null;
    }

    function updateVideoPreview(url) {
        const container = document.getElementById('video-preview-container');
        const iframe = document.getElementById('video-preview-iframe');
        const parsed = parseVideoUrl(url);

        if (parsed) {
            iframe.src = parsed.embedUrl;
            container.classList.remove('hidden');
        } else {
            iframe.src = '';
            container.classList.add('hidden');
        }
    }

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

    // Initialize preview if old value exists
    document.addEventListener('DOMContentLoaded', function() {
        const urlInput = document.getElementById('video_url');
        if (urlInput.value) {
            updateVideoPreview(urlInput.value);
        }
    });
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('title', 'Tambah Video')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.videos.index') }}" class="text-sm text-gray-600 hover:text-gray-800">&larr; Kembali ke Daftar Video</a>
    </div>

    <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Video</h2>

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Video URL -->
            <div class="mb-4">
                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">URL Video (YouTube/Vimeo) <span class="text-red-500">*</span></label>
                <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}" required
                    placeholder="https://www.youtube.com/watch?v=... atau https://vimeo.com/..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                    oninput="updateVideoPreview(this.value)">
                <p class="mt-1 text-xs text-gray-500">Masukkan URL YouTube atau Vimeo yang valid.</p>
                @error('video_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Video Embed Preview -->
            <div id="video-preview-container" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Preview</label>
                <div class="aspect-video w-full max-w-lg rounded-lg overflow-hidden bg-gray-100">
                    <iframe id="video-preview-iframe" src="" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="mb-4">
                <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                    onchange="previewThumbnail(this)">
                <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, WebP. Maksimal 2MB. Opsional — jika tidak diupload, thumbnail akan diambil dari video.</p>
                @error('thumbnail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="thumbnail-preview" class="mt-2 hidden">
                    <img loading="lazy" id="thumbnail-img" src="" alt="Preview" class="w-48 h-32 object-cover rounded-lg">
                </div>
            </div>

            <!-- Is Active -->
            <div class="mb-4">
                <label class="flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                        class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-700">Aktif (tampilkan di halaman publik)</span>
                </label>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                Simpan Video
            </button>
            <a href="{{ route('admin.videos.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300">
                Batal
            </a>
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

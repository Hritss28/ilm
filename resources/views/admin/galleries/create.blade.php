@extends('layouts.admin')

@section('title', 'Buat Gallery')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.galleries.index') }}" class="text-sm text-gray-600 hover:text-gray-800">&larr; Kembali ke Daftar Gallery</a>
    </div>

    <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data" id="gallery-form">
        @csrf

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Gallery</h2>

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
                <textarea name="description" id="description" rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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

        <!-- Image Upload Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Upload Gambar <span class="text-red-500">*</span></h2>
            <p class="text-sm text-gray-500 mb-4">Maksimal 20 gambar. Format: JPEG, PNG, WebP. Ukuran maks 5MB per gambar.</p>

            @error('images')
                <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <!-- Drag and Drop Area -->
            <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-red-400 transition-colors cursor-pointer"
                 onclick="document.getElementById('file-input').click()">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="mt-2 text-sm text-gray-600">Drag & drop gambar di sini, atau <span class="text-red-600 font-medium">klik untuk memilih</span></p>
                <p class="mt-1 text-xs text-gray-500">Pilih beberapa file sekaligus (maks 20)</p>
            </div>

            <input type="file" id="file-input" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden" onchange="handleFiles(this.files)">

            <!-- Image Preview Grid -->
            <div id="image-preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden">
            </div>

            <p id="image-count" class="mt-2 text-sm text-gray-500 hidden">
                <span id="count-number">0</span>/20 gambar dipilih
            </p>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                Simpan Gallery
            </button>
            <a href="{{ route('admin.galleries.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let selectedFiles = [];
    const maxFiles = 20;

    const dropZone = document.getElementById('drop-zone');
    const previewGrid = document.getElementById('image-preview-grid');
    const imageCount = document.getElementById('image-count');
    const countNumber = document.getElementById('count-number');

    // Drag and drop events
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-red-400', 'bg-red-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-red-400', 'bg-red-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-red-400', 'bg-red-50');
        handleFiles(e.dataTransfer.files);
    });

    function handleFiles(files) {
        const newFiles = Array.from(files).filter(f => f.type.match(/^image\/(jpeg|png|webp)$/));

        if (selectedFiles.length + newFiles.length > maxFiles) {
            alert('Maksimal ' + maxFiles + ' gambar per gallery.');
            return;
        }

        selectedFiles = selectedFiles.concat(newFiles);
        updateFileInput();
        renderPreviews();
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        document.getElementById('file-input').files = dt.files;
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        renderPreviews();
    }

    function renderPreviews() {
        previewGrid.innerHTML = '';

        if (selectedFiles.length === 0) {
            previewGrid.classList.add('hidden');
            imageCount.classList.add('hidden');
            return;
        }

        previewGrid.classList.remove('hidden');
        imageCount.classList.remove('hidden');
        countNumber.textContent = selectedFiles.length;

        selectedFiles.forEach((file, index) => {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.setAttribute('draggable', 'true');
            div.setAttribute('data-index', index);

            const reader = new FileReader();
            reader.onload = function(e) {
                div.innerHTML = `
                    <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                        <img loading="lazy" src="${e.target.result}" alt="" class="w-full h-full object-cover">
                    </div>
                    <button type="button" onclick="removeImage(${index})" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                        &times;
                    </button>
                    <div class="mt-1">
                        <input type="text" name="captions[${index}]" placeholder="Caption..." class="w-full text-xs rounded border-gray-300 px-2 py-1">
                    </div>
                    <div class="absolute bottom-8 left-1 bg-black bg-opacity-60 text-white text-xs px-1.5 py-0.5 rounded">
                        ${index + 1}
                    </div>
                `;
            };
            reader.readAsDataURL(file);

            // Drag reorder
            div.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', index);
                this.classList.add('opacity-50');
            });

            div.addEventListener('dragend', function() {
                this.classList.remove('opacity-50');
            });

            div.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            div.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const fromIndex = parseInt(e.dataTransfer.getData('text/plain'));
                const toIndex = index;
                if (fromIndex !== toIndex) {
                    const [moved] = selectedFiles.splice(fromIndex, 1);
                    selectedFiles.splice(toIndex, 0, moved);
                    updateFileInput();
                    renderPreviews();
                }
            });

            previewGrid.appendChild(div);
        });
    }
</script>
@endpush
@endsection

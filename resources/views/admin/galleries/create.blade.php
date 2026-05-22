@extends('layouts.admin')

@section('title', 'Tambah Kelana Kota')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Kelana Kota</h1>
    </div>

    <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data" id="gallery-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Kelana Kota</h2>

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

                <!-- Image Upload Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Upload Gambar <span class="text-red-500">*</span></h2>
                    <p class="text-sm text-gray-500 mb-4">Maksimal 20 gambar. Format: JPEG, PNG, WebP. Ukuran maks 5MB per gambar.</p>

                    @error('images')
                        <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Drag and Drop Area -->
                    <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-red-400 transition-colors cursor-pointer bg-gray-50"
                         onclick="document.getElementById('file-input').click()">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Drag & drop gambar di sini, atau <span class="text-red-600 font-medium">klik untuk memilih</span></p>
                        <p class="mt-1 text-xs text-gray-500">Pilih beberapa file sekaligus (maks 20)</p>
                    </div>

                    <input type="file" id="file-input" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden" onchange="handleFiles(this.files)">

                    <!-- Image Preview Grid -->
                    <div id="image-preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 hidden">
                    </div>

                    <p id="image-count" class="mt-2 text-sm text-gray-500 hidden text-right">
                        <span id="count-number">0</span>/20 gambar dipilih
                    </p>
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
                            Simpan Kelana Kota
                        </button>
                        <a href="{{ route('admin.galleries.index') }}" class="w-full py-2.5 px-4 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors text-center border border-gray-300">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
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

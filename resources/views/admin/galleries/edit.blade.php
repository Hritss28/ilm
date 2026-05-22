@extends('layouts.admin')

@section('title', 'Edit Kelana Kota')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Kelana Kota</h1>
    </div>

    <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data" id="gallery-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Kelana Kota</h2>

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-lg">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('description', $gallery->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Existing Images -->
                @if($gallery->images->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Gambar Saat Ini</h2>
                    <p class="text-sm text-gray-500 mb-4">Drag untuk mengubah urutan. Klik &times; untuk menghapus gambar.</p>

                    <div id="existing-images-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($gallery->images->sortBy('order') as $image)
                        <div class="relative group" draggable="true" data-image-id="{{ $image->id }}">
                            <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                <img loading="lazy" src="{{ Storage::url($image->image_url) }}" alt="{{ $image->caption }}" class="w-full h-full object-cover">
                            </div>
                            <button type="button" onclick="removeExistingImage(this)" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                &times;
                            </button>
                            <div class="absolute bottom-1 left-1 bg-black bg-opacity-60 text-white text-xs px-1.5 py-0.5 rounded">
                                {{ $loop->iteration }}
                            </div>
                            @if($image->caption)
                            <p class="mt-1 text-xs text-gray-500 truncate">{{ $image->caption }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Hidden field to store reordered image IDs -->
                    <input type="hidden" name="image_order" id="image-order" value="">
                </div>
                @endif

                <!-- Upload New Images -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Tambah Gambar Baru</h2>
                    <p class="text-sm text-gray-500 mb-4">Maksimal 20 gambar total. Format: JPEG, PNG, WebP. Ukuran maks 5MB per gambar.</p>

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
                        <p class="mt-1 text-xs text-gray-500">Pilih beberapa file sekaligus</p>
                    </div>

                    <input type="file" id="file-input" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden" onchange="handleNewFiles(this.files)">

                    <!-- New Image Preview Grid -->
                    <div id="new-image-preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 hidden">
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
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 w-5 h-5">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-700">Aktif</span>
                                <span class="block text-xs text-gray-500">Tampilkan di halaman publik</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" onclick="prepareSubmit()" class="w-full py-2.5 px-4 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Update Kelana Kota
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
    let newFiles = [];
    const maxFiles = 20;

    const dropZone = document.getElementById('drop-zone');
    const newPreviewGrid = document.getElementById('new-image-preview-grid');

    // Drag and drop for new files
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
        handleNewFiles(e.dataTransfer.files);
    });

    function handleNewFiles(files) {
        const existingCount = document.querySelectorAll('#existing-images-grid [data-image-id]').length;
        const validFiles = Array.from(files).filter(f => f.type.match(/^image\/(jpeg|png|webp)$/));

        if (existingCount + newFiles.length + validFiles.length > maxFiles) {
            alert('Maksimal ' + maxFiles + ' gambar total per gallery.');
            return;
        }

        newFiles = newFiles.concat(validFiles);
        updateNewFileInput();
        renderNewPreviews();
    }

    function updateNewFileInput() {
        const dt = new DataTransfer();
        newFiles.forEach(file => dt.items.add(file));
        document.getElementById('file-input').files = dt.files;
    }

    function removeNewImage(index) {
        newFiles.splice(index, 1);
        updateNewFileInput();
        renderNewPreviews();
    }

    function renderNewPreviews() {
        newPreviewGrid.innerHTML = '';

        if (newFiles.length === 0) {
            newPreviewGrid.classList.add('hidden');
            return;
        }

        newPreviewGrid.classList.remove('hidden');

        newFiles.forEach((file, index) => {
            const div = document.createElement('div');
            div.className = 'relative group';

            const reader = new FileReader();
            reader.onload = function(e) {
                div.innerHTML = `
                    <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                        <img loading="lazy" src="${e.target.result}" alt="" class="w-full h-full object-cover">
                    </div>
                    <button type="button" onclick="removeNewImage(${index})" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                        &times;
                    </button>
                    <div class="mt-1">
                        <input type="text" name="captions[${index}]" placeholder="Caption..." class="w-full text-xs rounded border-gray-300 px-2 py-1">
                    </div>
                `;
            };
            reader.readAsDataURL(file);

            newPreviewGrid.appendChild(div);
        });
    }

    // Existing images drag reorder
    const existingGrid = document.getElementById('existing-images-grid');
    if (existingGrid) {
        let draggedItem = null;

        existingGrid.querySelectorAll('[draggable="true"]').forEach(item => {
            item.addEventListener('dragstart', function(e) {
                draggedItem = this;
                this.classList.add('opacity-50');
            });

            item.addEventListener('dragend', function() {
                this.classList.remove('opacity-50');
                draggedItem = null;
                updateOrderNumbers();
            });

            item.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            item.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (draggedItem && draggedItem !== this) {
                    const allItems = [...existingGrid.children];
                    const fromIndex = allItems.indexOf(draggedItem);
                    const toIndex = allItems.indexOf(this);

                    if (fromIndex < toIndex) {
                        existingGrid.insertBefore(draggedItem, this.nextSibling);
                    } else {
                        existingGrid.insertBefore(draggedItem, this);
                    }
                }
            });
        });
    }

    function updateOrderNumbers() {
        if (!existingGrid) return;
        const items = existingGrid.querySelectorAll('[data-image-id]');
        items.forEach((item, index) => {
            const badge = item.querySelector('.absolute.bottom-1');
            if (badge) badge.textContent = index + 1;
        });
    }

    function removeExistingImage(button) {
        const item = button.closest('[data-image-id]');
        item.remove();
        updateOrderNumbers();
    }

    function prepareSubmit() {
        if (!existingGrid) return;
        const items = existingGrid.querySelectorAll('[data-image-id]');
        const order = Array.from(items).map(item => item.dataset.imageId);
        document.getElementById('image-order').value = JSON.stringify(order);
    }
</script>
@endpush
@endsection

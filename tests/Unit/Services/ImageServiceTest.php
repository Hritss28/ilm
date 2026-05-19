<?php

namespace Tests\Unit\Services;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    protected ImageService $imageService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->imageService = new ImageService();
    }

    // ─── Upload Thumbnail: Valid Formats ─────────────────────────────────────

    public function test_upload_thumbnail_accepts_jpeg(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(1024);

        $path = $this->imageService->uploadThumbnail($file);

        $this->assertNotEmpty($path);
        $this->assertStringStartsWith('thumbnails/', $path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_upload_thumbnail_accepts_png(): void
    {
        $file = UploadedFile::fake()->image('photo.png', 800, 600)->size(1024);

        $path = $this->imageService->uploadThumbnail($file);

        $this->assertNotEmpty($path);
        $this->assertStringStartsWith('thumbnails/', $path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_upload_thumbnail_accepts_webp(): void
    {
        $file = UploadedFile::fake()->image('photo.webp', 800, 600)->size(1024);

        $path = $this->imageService->uploadThumbnail($file);

        $this->assertNotEmpty($path);
        $this->assertStringStartsWith('thumbnails/', $path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    // ─── Upload Thumbnail: Invalid Formats ───────────────────────────────────

    public function test_upload_thumbnail_rejects_gif(): void
    {
        $file = UploadedFile::fake()->image('photo.gif', 800, 600)->size(1024);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid file format');

        $this->imageService->uploadThumbnail($file);
    }

    public function test_upload_thumbnail_rejects_bmp(): void
    {
        // Create a fake file with BMP mime type
        $file = UploadedFile::fake()->create('photo.bmp', 1024, 'image/bmp');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid file format');

        $this->imageService->uploadThumbnail($file);
    }

    // ─── Upload Thumbnail: Size Validation ───────────────────────────────────

    public function test_upload_thumbnail_rejects_oversized_file(): void
    {
        // Create a file larger than 2MB (2048KB)
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(3000);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File size exceeds maximum allowed size');

        $this->imageService->uploadThumbnail($file);
    }

    public function test_upload_thumbnail_accepts_file_at_max_size(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(2048);

        $path = $this->imageService->uploadThumbnail($file);

        $this->assertNotEmpty($path);
        Storage::disk('public')->assertExists($path);
    }

    // ─── Upload Thumbnail: Resize Behavior ───────────────────────────────────

    public function test_upload_thumbnail_stores_in_year_month_directory(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(1024);

        $path = $this->imageService->uploadThumbnail($file);

        $year = now()->format('Y');
        $month = now()->format('m');
        $this->assertStringContainsString("thumbnails/{$year}/{$month}/", $path);
    }

    public function test_upload_thumbnail_uses_custom_directory(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(1024);

        $path = $this->imageService->uploadThumbnail($file, 'custom-dir');

        $this->assertStringStartsWith('custom-dir/', $path);
    }

    // ─── Upload Gallery Image ────────────────────────────────────────────────

    public function test_upload_gallery_image_stores_in_gallery_directory(): void
    {
        $file = UploadedFile::fake()->image('gallery.jpg', 1920, 1080)->size(3000);

        $path = $this->imageService->uploadGalleryImage($file, 42);

        $this->assertStringStartsWith('galleries/42/', $path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_upload_gallery_image_rejects_oversized_file(): void
    {
        $file = UploadedFile::fake()->image('gallery.jpg', 1920, 1080)->size(6000);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File size exceeds maximum allowed size');

        $this->imageService->uploadGalleryImage($file, 1);
    }

    public function test_upload_gallery_image_rejects_invalid_format(): void
    {
        $file = UploadedFile::fake()->create('gallery.bmp', 1024, 'image/bmp');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid file format');

        $this->imageService->uploadGalleryImage($file, 1);
    }

    // ─── Upload Advertisement ────────────────────────────────────────────────

    public function test_upload_advertisement_stores_file(): void
    {
        $file = UploadedFile::fake()->image('ad.jpg', 728, 90)->size(500);

        $path = $this->imageService->uploadAdvertisement($file);

        $this->assertStringStartsWith('advertisements/', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_upload_advertisement_rejects_oversized_file(): void
    {
        $file = UploadedFile::fake()->image('ad.jpg', 728, 90)->size(6000);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File size exceeds maximum allowed size');

        $this->imageService->uploadAdvertisement($file);
    }

    // ─── Delete Image ────────────────────────────────────────────────────────

    public function test_delete_removes_file_from_storage(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(1024);
        $path = $this->imageService->uploadThumbnail($file);

        Storage::disk('public')->assertExists($path);

        $this->imageService->delete($path);

        Storage::disk('public')->assertMissing($path);
    }
}

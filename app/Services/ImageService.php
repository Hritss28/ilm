<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class ImageService
{
    /**
     * Allowed MIME types for thumbnails.
     */
    protected array $allowedThumbnailMimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    /**
     * Maximum thumbnail file size in kilobytes.
     */
    protected int $maxThumbnailSize;

    /**
     * Maximum gallery image file size in kilobytes.
     */
    protected int $maxGallerySize;

    /**
     * Maximum advertisement file size in kilobytes.
     */
    protected int $maxAdvertisementSize;

    /**
     * Image quality for WebP conversion.
     */
    protected int $quality;

    /**
     * Image manager instance.
     */
    protected ImageManager $manager;

    public function __construct()
    {
        $this->maxThumbnailSize = config('news_portal.images.max_thumbnail_size', 2048);
        $this->maxGallerySize = config('news_portal.images.max_gallery_image_size', 5120);
        $this->maxAdvertisementSize = config('news_portal.images.max_advertisement_size', 5120);
        $this->quality = config('news_portal.images.quality', 85);
        $this->manager = new ImageManager(GdDriver::class);
    }

    /**
     * Upload and process a thumbnail image.
     *
     * Validates format (JPEG, PNG, WebP), max 2MB.
     * Resizes to max 1200px width maintaining aspect ratio.
     * Converts to WebP format with configured quality.
     * Stores in storage/app/public/{directory}/{year}/{month}/{filename}.webp
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory The base directory for storage
     * @return string The stored path relative to the public disk
     *
     * @throws \InvalidArgumentException If file format or size is invalid
     */
    public function uploadThumbnail(UploadedFile $file, string $directory = 'thumbnails'): string
    {
        $this->validateFormat($file);
        $this->validateSize($file, $this->maxThumbnailSize);

        $image = $this->manager->decodePath($file->getPathname());

        // Resize to max 1200px width, maintaining aspect ratio
        $image = $this->resizeToMaxWidth($image, 1200);

        // Convert to WebP
        $encoded = $image->encode(new WebpEncoder($this->quality));

        // Generate storage path: {directory}/{year}/{month}/{filename}.webp
        $filename = $this->generateFilename();
        $path = sprintf(
            '%s/%s/%s/%s.webp',
            $directory,
            now()->format('Y'),
            now()->format('m'),
            $filename
        );

        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Upload and process a gallery image.
     *
     * Max 5MB, optimizes for web (max 1920x1080, quality 90%).
     * Stores in storage/app/public/galleries/{gallery_id}/{filename}.webp
     *
     * @param UploadedFile $file The uploaded file
     * @param int $galleryId The gallery ID for organizing files
     * @return string The stored path relative to the public disk
     *
     * @throws \InvalidArgumentException If file format or size is invalid
     */
    public function uploadGalleryImage(UploadedFile $file, int $galleryId): string
    {
        $this->validateFormat($file);
        $this->validateSize($file, $this->maxGallerySize);

        $image = $this->manager->decodePath($file->getPathname());

        // Resize to max 1920x1080, maintaining aspect ratio
        $width = $image->width();
        $height = $image->height();

        if ($width > 1920 || $height > 1080) {
            $image = $image->scaleDown(1920, 1080);
        }

        // Convert to WebP with 90% quality for gallery images
        $encoded = $image->encode(new WebpEncoder(90));

        $filename = $this->generateFilename();
        $path = sprintf('galleries/%d/%s.webp', $galleryId, $filename);

        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Upload an advertisement image.
     *
     * Max 5MB, stores as-is in storage/app/public/advertisements/
     *
     * @param UploadedFile $file The uploaded file
     * @return string The stored path relative to the public disk
     *
     * @throws \InvalidArgumentException If file size is invalid
     */
    public function uploadAdvertisement(UploadedFile $file): string
    {
        $this->validateSize($file, $this->maxAdvertisementSize);

        $path = $file->store('advertisements', 'public');

        return $path;
    }

    /**
     * Delete a file from the public disk.
     *
     * @param string $path The path relative to the public disk
     */
    public function delete(string $path): void
    {
        Storage::disk('public')->delete($path);
    }

    /**
     * Validate that the file has an allowed MIME type.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateFormat(UploadedFile $file): void
    {
        $mime = $file->getMimeType();

        if (!in_array($mime, $this->allowedThumbnailMimes)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid file format. Allowed formats: JPEG, PNG, WebP. Got: %s',
                    $mime
                )
            );
        }
    }

    /**
     * Validate that the file does not exceed the maximum size.
     *
     * @param UploadedFile $file
     * @param int $maxSizeKb Maximum size in kilobytes
     * @throws \InvalidArgumentException
     */
    protected function validateSize(UploadedFile $file, int $maxSizeKb): void
    {
        $fileSizeKb = $file->getSize() / 1024;

        if ($fileSizeKb > $maxSizeKb) {
            throw new \InvalidArgumentException(
                sprintf(
                    'File size exceeds maximum allowed size of %dKB. Got: %dKB',
                    $maxSizeKb,
                    (int) $fileSizeKb
                )
            );
        }
    }

    /**
     * Resize image to a maximum width while maintaining aspect ratio.
     */
    protected function resizeToMaxWidth(ImageInterface $image, int $maxWidth): ImageInterface
    {
        if ($image->width() > $maxWidth) {
            $image = $image->scaleDown($maxWidth);
        }

        return $image;
    }

    /**
     * Generate a unique filename.
     */
    protected function generateFilename(): string
    {
        return uniqid() . '_' . time();
    }
}

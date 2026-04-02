<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * ImageService — Phase 9-G
 * Handles image upload with automatic resizing to three versions:
 * - thumbnail (300×300, cropped)
 * - medium (600×600, fitted)
 * - original (max 1200px on longest side, quality 80)
 */
class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Store an uploaded image with three sized versions.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory Sub-directory inside storage (e.g. 'products', 'categories')
     * @return array ['original' => path, 'medium' => path, 'thumbnail' => path]
     */
    public function store(UploadedFile $file, string $directory): array
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $baseName = pathinfo($filename, PATHINFO_FILENAME);

        // Ensure directories exist
        $basePath = "{$directory}";
        Storage::disk('public')->makeDirectory("{$basePath}/original");
        Storage::disk('public')->makeDirectory("{$basePath}/medium");
        Storage::disk('public')->makeDirectory("{$basePath}/thumbnail");

        $image = $this->manager->read($file->getPathname());

        // Original — max 1200px on longest side, quality 80
        $original = clone $image;
        $original->scaleDown(width: 1200, height: 1200);
        $originalPath = "{$basePath}/original/{$filename}";
        Storage::disk('public')->put($originalPath, $original->toJpeg(quality: 80)->toString());

        // Medium — 600×600, fitted (contain)
        $medium = clone $image;
        $medium->scaleDown(width: 600, height: 600);
        $mediumPath = "{$basePath}/medium/{$filename}";
        Storage::disk('public')->put($mediumPath, $medium->toJpeg(quality: 85)->toString());

        // Thumbnail — 300×300, cropped (cover)
        $thumbnail = clone $image;
        $thumbnail->cover(300, 300);
        $thumbnailPath = "{$basePath}/thumbnail/{$filename}";
        Storage::disk('public')->put($thumbnailPath, $thumbnail->toJpeg(quality: 85)->toString());

        // Try WebP versions if supported
        $webpPaths = $this->storeWebpVersions($image, $basePath, $baseName);

        return [
            'original' => $originalPath,
            'medium' => $mediumPath,
            'thumbnail' => $thumbnailPath,
            'filename' => $filename,
            'webp' => $webpPaths,
        ];
    }

    /**
     * Store WebP versions alongside originals.
     */
    protected function storeWebpVersions($image, string $basePath, string $baseName): array
    {
        $paths = [];

        try {
            Storage::disk('public')->makeDirectory("{$basePath}/original/webp");
            Storage::disk('public')->makeDirectory("{$basePath}/medium/webp");
            Storage::disk('public')->makeDirectory("{$basePath}/thumbnail/webp");

            // Original WebP
            $original = clone $image;
            $original->scaleDown(width: 1200, height: 1200);
            $paths['original'] = "{$basePath}/original/webp/{$baseName}.webp";
            Storage::disk('public')->put($paths['original'], $original->toWebp(quality: 80)->toString());

            // Medium WebP
            $medium = clone $image;
            $medium->scaleDown(width: 600, height: 600);
            $paths['medium'] = "{$basePath}/medium/webp/{$baseName}.webp";
            Storage::disk('public')->put($paths['medium'], $medium->toWebp(quality: 85)->toString());

            // Thumbnail WebP
            $thumbnail = clone $image;
            $thumbnail->cover(300, 300);
            $paths['thumbnail'] = "{$basePath}/thumbnail/webp/{$baseName}.webp";
            Storage::disk('public')->put($paths['thumbnail'], $thumbnail->toWebp(quality: 85)->toString());
        } catch (\Exception $e) {
            // WebP may not be supported — skip silently
            \Illuminate\Support\Facades\Log::debug('WebP conversion skipped: ' . $e->getMessage());
        }

        return $paths;
    }

    /**
     * Delete all versions of an image.
     */
    public function delete(string $directory, string $filename): void
    {
        $baseName = pathinfo($filename, PATHINFO_FILENAME);

        $paths = [
            "{$directory}/original/{$filename}",
            "{$directory}/medium/{$filename}",
            "{$directory}/thumbnail/{$filename}",
            "{$directory}/original/webp/{$baseName}.webp",
            "{$directory}/medium/webp/{$baseName}.webp",
            "{$directory}/thumbnail/webp/{$baseName}.webp",
        ];

        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Get the URL for a specific image version.
     */
    public static function url(?string $path, string $size = 'original'): string
    {
        if (!$path) {
            return asset('themes/porto/images/products/product-1.jpg');
        }

        // If path already contains the size folder, return as-is
        if (str_contains($path, '/original/') || str_contains($path, '/medium/') || str_contains($path, '/thumbnail/')) {
            return asset('storage/' . $path);
        }

        // Try to construct sized path
        $dir = dirname($path);
        $file = basename($path);
        $sizedPath = "{$dir}/{$size}/{$file}";

        if (Storage::disk('public')->exists($sizedPath)) {
            return asset('storage/' . $sizedPath);
        }

        return asset('storage/' . $path);
    }
}

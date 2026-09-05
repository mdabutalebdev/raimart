<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resizes uploaded images and stores them as WebP, which typically cuts a
 * 1.5 MB phone photo down to well under 100 KB with no visible quality loss.
 * Uses PHP's bundled GD — no extra package or binary needed.
 *
 * Falls back to storing the original file untouched if anything goes wrong,
 * so an upload can never fail because of optimisation.
 */
class ImageOptimizer
{
    /** Longest edge (px) per usage, so we never store more than we display. */
    public const PRESETS = [
        'products' => 1200,
        'banners' => 1920,
        'categories' => 800,
        'brands' => 600,
        'testimonials' => 400,
        'avatars' => 400,
        'about' => 1400,
        'default' => 1200,
    ];

    public const QUALITY = 82;

    /** Videos and animated GIFs are stored as-is. */
    private const PASSTHROUGH = ['gif', 'svg', 'mp4', 'webm', 'mov', 'avi'];

    /**
     * Store an uploaded image optimised, returning the stored path
     * (same contract as $file->store($folder, 'public')).
     */
    public function store(UploadedFile $file, string $folder, ?int $maxWidth = null): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, self::PASSTHROUGH, true) || ! $this->canProcess()) {
            return $file->store($folder, 'public');
        }

        $maxWidth ??= self::PRESETS[$folder] ?? self::PRESETS['default'];
        $path = $folder.'/'.Str::random(40).'.webp';

        try {
            $webp = $this->toWebp($file->getRealPath(), $maxWidth);

            if ($webp === null) {
                return $file->store($folder, 'public');
            }

            Storage::disk('public')->put($path, $webp);

            return $path;
        } catch (\Throwable $e) {
            Log::warning('Image optimisation failed, storing original', ['error' => $e->getMessage()]);

            return $file->store($folder, 'public');
        }
    }

    /**
     * Optimise a file already sitting on the public disk, in place.
     * Returns the new path, or null when nothing changed.
     */
    public function optimiseExisting(string $path, ?int $maxWidth = null): ?string
    {
        $disk = Storage::disk('public');
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($extension, self::PASSTHROUGH, true) || ! $disk->exists($path) || ! $this->canProcess()) {
            return null;
        }

        $folder = dirname($path);
        $maxWidth ??= self::PRESETS[basename($folder)] ?? self::PRESETS['default'];

        $webp = $this->toWebp($disk->path($path), $maxWidth);

        if ($webp === null) {
            return null;
        }

        $newPath = $folder.'/'.pathinfo($path, PATHINFO_FILENAME).'.webp';

        // Only keep the new file if it is genuinely smaller.
        if (strlen($webp) >= $disk->size($path) && $extension === 'webp') {
            return null;
        }

        $disk->put($newPath, $webp);

        if ($newPath !== $path) {
            $disk->delete($path);
        }

        return $newPath;
    }

    public function canProcess(): bool
    {
        return extension_loaded('gd') && function_exists('imagewebp');
    }

    /** Read an image, scale it down to $maxWidth and return WebP bytes. */
    private function toWebp(string $absolutePath, int $maxWidth): ?string
    {
        $info = @getimagesize($absolutePath);

        if (! $info) {
            return null;
        }

        $source = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($absolutePath),
            IMAGETYPE_PNG => @imagecreatefrompng($absolutePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($absolutePath),
            default => null,
        };

        if (! $source) {
            return null;
        }

        [$width, $height] = [imagesx($source), imagesy($source)];
        $scale = min(1, $maxWidth / max($width, $height));
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        // Keep PNG transparency intact.
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 255, 255, 255, 127));

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagewebp($canvas, null, self::QUALITY);
        $bytes = ob_get_clean();

        imagedestroy($source);
        imagedestroy($canvas);

        return $bytes ?: null;
    }
}

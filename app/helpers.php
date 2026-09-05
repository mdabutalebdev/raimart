<?php

if (! function_exists('image_url')) {
    function image_url(?string $path, string $placeholderText = 'No+Image'): string
    {
        if (! $path) {
            return "https://placehold.co/400x400/F5F2F2/083369?text={$placeholderText}";
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/'.$path);
    }
}

if (! function_exists('is_video')) {
    /**
     * Whether a stored media path points at a video file.
     */
    function is_video(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        $extension = strtolower(pathinfo(parse_url($path, PHP_URL_PATH) ?? $path, PATHINFO_EXTENSION));

        return in_array($extension, ['mp4', 'mov', 'webm', 'ogv', 'ogg', 'm4v', 'avi', 'mkv', '3gp'], true);
    }
}

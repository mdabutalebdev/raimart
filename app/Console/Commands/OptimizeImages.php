<?php

namespace App\Console\Commands;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Services\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Re-compresses every image already uploaded and repoints the database at the
 * new WebP files. Safe to run more than once — already-optimised files are skipped.
 */
class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--dry-run : Report the savings without changing anything}';

    protected $description = 'Convert existing uploaded images to resized WebP and update the database';

    public function handle(ImageOptimizer $optimizer): int
    {
        if (! $optimizer->canProcess()) {
            $this->error('PHP GD with WebP support is required.');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $disk = Storage::disk('public');

        // [model class or null, column, label] — null means it lives in site_settings
        $targets = [
            [Product::class, 'main_image', 'Products'],
            [ProductImage::class, 'image', 'Product gallery'],
            [Category::class, 'image', 'Categories'],
            [Brand::class, 'logo', 'Brands'],
            [Banner::class, 'image', 'Banners'],
            [Testimonial::class, 'avatar', 'Testimonials'],
        ];

        $before = 0;
        $after = 0;
        $count = 0;

        foreach ($targets as [$model, $column, $label]) {
            $rows = $model::query()->whereNotNull($column)->where($column, '!=', '')->get();
            $this->line("<comment>{$label}</comment> ({$rows->count()})");

            foreach ($rows as $row) {
                $path = $row->getRawOriginal($column) ?? $row->{$column};

                if (! $path || str_starts_with($path, 'http') || ! $disk->exists($path)) {
                    continue;
                }

                $sizeBefore = $disk->size($path);

                if ($dryRun) {
                    $before += $sizeBefore;
                    $count++;
                    continue;
                }

                $newPath = $optimizer->optimiseExisting($path);

                if (! $newPath) {
                    continue;
                }

                $row->forceFill([$column => $newPath])->save();

                $before += $sizeBefore;
                $after += $disk->size($newPath);
                $count++;

                $this->line("  ".basename($path)." → ".$this->human($sizeBefore)." → ".$this->human($disk->size($newPath)));
            }
        }

        // The About page image lives in site_settings, not on a model.
        if ($path = SiteSetting::get('about_image')) {
            if ($disk->exists($path) && ! str_starts_with($path, 'http')) {
                $sizeBefore = $disk->size($path);

                if (! $dryRun && $newPath = $optimizer->optimiseExisting($path)) {
                    SiteSetting::set('about_image', $newPath);
                    $before += $sizeBefore;
                    $after += $disk->size($newPath);
                    $count++;
                }
            }
        }

        $this->newLine();

        if ($dryRun) {
            $this->info("Dry run: {$count} images totalling ".$this->human($before).' would be optimised.');

            return self::SUCCESS;
        }

        $saved = max(0, $before - $after);
        $percent = $before > 0 ? round($saved / $before * 100) : 0;

        $this->info("Optimised {$count} images: ".$this->human($before).' → '.$this->human($after)." (saved {$percent}%)");

        return self::SUCCESS;
    }

    private function human(int $bytes): string
    {
        return $bytes >= 1048576
            ? round($bytes / 1048576, 1).' MB'
            : round($bytes / 1024).' KB';
    }
}

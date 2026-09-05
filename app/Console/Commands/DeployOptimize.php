<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * One command to run after deploying: rebuild every Laravel cache so the app
 * stops re-parsing config, routes and Blade templates on each request.
 */
class DeployOptimize extends Command
{
    protected $signature = 'deploy:optimize {--fresh : Clear caches first (use after a config change)}';

    protected $description = 'Cache config, routes, views and events for production';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->components->task('Clearing old caches', function () {
                $this->callSilently('optimize:clear');

                return true;
            });
        }

        foreach ([
            'config:cache' => 'Caching config',
            'route:cache' => 'Caching routes',
            'view:cache' => 'Compiling Blade views',
            'event:cache' => 'Caching events',
        ] as $command => $label) {
            $this->components->task($label, function () use ($command) {
                $this->callSilently($command);

                return true;
            });
        }

        $this->components->task('Warming application caches', function () {
            // Touch the cached fragments so the first visitor doesn't pay for them.
            \App\Models\SiteSetting::flushCache();
            \App\Models\SiteSetting::getAll();

            return true;
        });

        $this->newLine();
        $this->components->info('Deployment caches are ready.');
        $this->line('  Remember to run <comment>npm run build</comment> for the CSS/JS bundle.');

        return self::SUCCESS;
    }
}

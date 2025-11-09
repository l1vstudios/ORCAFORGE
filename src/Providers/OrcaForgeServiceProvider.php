<?php

namespace Orcaforge\Providers;

use Illuminate\Support\ServiceProvider;

class OrcaForgeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'orcaforge');

        $migrationPath = __DIR__ . '/../database/migrations';
        if (is_dir($migrationPath)) {
            $this->loadMigrationsFrom($migrationPath);

            $this->publishes([
                $migrationPath => database_path('migrations'),
            ], 'orcaforge-migrations');
        }
        //ORCAAAA
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/orcaforge'),
        ], 'orcaforge-views');

        $this->publishes([
            __DIR__ . '/../../resources/css' => public_path('vendor/orcaforge/css'),
            __DIR__ . '/../../resources/js'  => public_path('vendor/orcaforge/js'),
        ], 'orcaforge-assets');
    }
    public function register(): void
    {
        //
    }
}

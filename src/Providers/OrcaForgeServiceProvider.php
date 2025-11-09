<?php

namespace Orcaforge\Providers;

use Illuminate\Support\ServiceProvider;

class OrcaforgeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'orcaforge');

        // Publish views & assets
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/orcaforge'),
            __DIR__.'/../../resources/css' => resource_path('css/vendor/orcaforge'),
        ], 'orcaforge-assets');
    }

    public function register(): void
    {
        //
    }
}

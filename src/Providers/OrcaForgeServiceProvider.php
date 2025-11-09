<?php

namespace Orcaforge\Providers;

use Illuminate\Support\ServiceProvider;

class OrcaForgeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Load Routes & Views
        |--------------------------------------------------------------------------
        */
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'orcaforge');

        /*
        |--------------------------------------------------------------------------
        | Publikasi Views & Assets (CSS, JS)
        |--------------------------------------------------------------------------
        */
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/orcaforge'),
        ], 'orcaforge-views');

        $this->publishes([
            __DIR__ . '/../../resources/css' => public_path('vendor/orcaforge/css'),
            // Jika nanti kamu menambahkan JS di resources/js
            __DIR__ . '/../../resources/js' => public_path('vendor/orcaforge/js'),
        ], 'orcaforge-assets');

        /*
        |--------------------------------------------------------------------------
        | (Opsional) Publikasi Migration
        |--------------------------------------------------------------------------
        */
        if (file_exists(__DIR__ . '/../../database/migrations')) {
            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'orcaforge-migrations');
        }
    }

    public function register(): void
    {
        //
    }
}

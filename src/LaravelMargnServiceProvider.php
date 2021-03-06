<?php

namespace RnTorm\LaravelMargn;

use Illuminate\Support\ServiceProvider;

class LaravelMargnServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->handleConfigs();
    }

    public function handleConfigs()
    {
        $configPath = __DIR__.'/../config/config.php';

        $this->publishes([
            $configPath => config_path('laravel-margn.php'),
        ]);
    }
}

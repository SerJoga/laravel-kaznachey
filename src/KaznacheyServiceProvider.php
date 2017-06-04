<?php

namespace SerJoga\Kaznachey;

use Illuminate\Support\ServiceProvider;

class KaznacheyServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
           __DIR__ . '/migrations/' => base_path('/database/migrations'),
           __DIR__ . '/config/kazachey.php' => config_path('kazachey.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('kaznachey', 'SerJoga\Kaznachey\Api\Kaznachey');
    }
}

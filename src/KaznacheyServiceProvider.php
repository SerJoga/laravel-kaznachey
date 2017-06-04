<?php

namespace SerJoga\Kaznachey;

use Illuminate\Support\ServiceProvider;
use SerJoga\Kaznachey\Api\Kaznachey;

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
        $this->app->bind('kaznachey', function($app){
            return new Kaznachey(config('kaznachey.merchant_secret_key'),config('kaznachey.merchant_guid'));
        });
    }
}

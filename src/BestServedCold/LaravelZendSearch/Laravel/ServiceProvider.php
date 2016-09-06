<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../config/search.php', 'search');
    }

    /**
     * Register the application services.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function register()
    {
        $this->app->bind('search', function ($app) {
            return new Search;
        });
    }
}

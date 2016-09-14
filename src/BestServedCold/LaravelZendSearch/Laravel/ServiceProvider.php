<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Support\ServiceProvider as Provider;
use BestServedCold\LaravelZendSearch\Laravel\Console\Rebuild;
use BestServedCold\LaravelZendSearch\Laravel\Console\Destroy;
use BestServedCold\LaravelZendSearch\Laravel\Console\Optimise;

/**
 * Class ServiceProvider
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
class ServiceProvider extends Provider
{
    /**
     * Bootstrap the application services.
     *
     * @return             void
     * @codeCoverageIgnore
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../config/search.php', 'search');
    }

    /**
     * Register the application services.
     *
     * @return             void
     * @codeCoverageIgnore
     */
    public function register()
    {
        $this->publishes(
            [
            __DIR__ . '/../../../config/search.php' => config_path('search.php'),
            ]
        );

        $this->app->singleton(
            'command.search.rebuild', function() {
                return new Rebuild;
            }
        );

        $this->app->singleton(
            'command.search.destroy', function() {
                return new Destroy;
            }
        );

        $this->app->singleton(
            'command.search.optimise', function() {
                return new Optimise;
            }
        );

        $this->commands([ 'command.search.rebuild', 'command.search.optimise', 'command.search.destroy' ]);
    }


    /**
     * @return array
     */
    public function provides()
    {
        return [ 'search', 'command.search.rebuild', 'command.search.optimise', 'command.search.destroy' ];
    }
}

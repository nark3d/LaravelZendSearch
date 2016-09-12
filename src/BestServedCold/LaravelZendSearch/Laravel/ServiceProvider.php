<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Support\ServiceProvider as Provider;
use BestServedCold\LaravelZendSearch\Laravel\Console\RebuildCommand;
use BestServedCold\LaravelZendSearch\Laravel\Console\DestroyCommand;
use BestServedCold\LaravelZendSearch\Laravel\Console\OptimiseCommand;

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
                return new RebuildCommand;
            }
        );

        $this->app->singleton(
            'command.search.destroy', function() {
                return new DestroyCommand;
            }
        );

        $this->app->singleton(
            'command.search.optimise', function() {
                return new OptimiseCommand;
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

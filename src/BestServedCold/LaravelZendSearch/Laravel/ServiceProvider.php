<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Support\ServiceProvider as Provider;
use BestServedCold\LaravelZendSearch\Laravel\Console\RebuildCommand;
use BestServedCold\LaravelZendSearch\Laravel\Console\ClearCommand;
use BestServedCold\LaravelZendSearch\Laravel\Console\OptimiseCommand;

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
        $this->publishes([
            __DIR__ . '/../../../config/search.php' => config_path('search.php'),
        ]);

        $this->app->singleton('command.search.rebuild', function () {
            return new RebuildCommand;
        });

        $this->app->singleton('command.search.clear', function () {
            return new ClearCommand;
        });

        $this->app->singleton('command.search.optimise', function () {
            return new OptimiseCommand;
        });

        $this->commands(['command.search.rebuild', 'command.search.optimise', 'command.search.clear']);
    }


    public function provides()
    {
        return ['search', 'command.search.rebuild', 'command.search.optimise', 'command.search.clear'];
    }
}

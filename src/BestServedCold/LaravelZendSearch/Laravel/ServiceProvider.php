<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Console\Command;
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
     * @param string $command
     */
    private function registerCommand($command)
    {
        $reflection = new \ReflectionClass($command);
        $this->app->singleton(
            'command.search.' . strtolower($reflection->getShortName()),
            function() use ($command) {
                return new $command;
            }
        );
    }

    /**
     * Register the application services.
     *
     * @return             void
     * @codeCoverageIgnore
     */
    public function register()
    {
        $this->publishes([ __DIR__ . '/../../../config/search.php' => config_path('search.php'), ]);

        $this->registerCommand(Rebuild::class);
        $this->registerCommand(Destroy::class);
        $this->registerCommand(Optimise::class);

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

<?php

namespace BestServedCold\LaravelZendSearch;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return ['BestServedCold\LaravelZendSearch\Laravel\ServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return ['Search' => 'BestServedCold\LaravelZendSearch\Laravel\Facade'];
    }
}

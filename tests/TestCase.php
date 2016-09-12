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

    protected function reflectionProperty($object, $property)
    {
        $reflection = new \ReflectionObject($object);
        $reflectionProprety = $reflection->getProperty($property);
        $reflectionProprety->setAccessible(true);

        return $reflectionProprety->getValue($object);
    }

    protected function reflectionMethod($object, $method)
    {
        $reflection = new \ReflectionMethod($object);

    }
}

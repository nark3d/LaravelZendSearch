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
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }

    protected function reflectionMethod($object, $method, $arguments = null)
    {
        $method = new \ReflectionMethod($object, $method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $arguments);
    }
}

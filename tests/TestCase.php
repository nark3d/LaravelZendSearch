<?php

namespace BestServedCold\LaravelZendSearch;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $indexPath =  "./tests/tmp";

    public function setUp()
    {
        parent::setUp();
        $this->indexPath =  $this->indexPath . DIRECTORY_SEPARATOR . 'tempIndex';
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->clearDirectory($this->indexPath);
    }

    protected function getPackageProviders($app)
    {
        return ['BestServedCold\LaravelZendSearch\Laravel\ServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return ['Search' => 'BestServedCold\LaravelZendSearch\Laravel\Facade'];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('search.index.path', 'tests/tmp/tempIndex');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));
    }

    protected function reflectionProperty($object, $property)
    {
        $reflection = new \ReflectionObject($object);
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }

    protected function reflectionMethod($object, $method, $arguments = [])
    {
        $method = new \ReflectionMethod($object, $method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $arguments);
    }


    protected function clearDirectory($path)
    {
        if (file_exists($path)) {
            // Am I windows?
            $windows  = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

            chmod($path, 0777);
            $dir = opendir($path);
            while (($file = readdir($dir)) !== false) {
                $abs = $path . DIRECTORY_SEPARATOR . $file;
                chmod($abs, 0777);
                if (!is_dir($abs)) {
                    $windows ? exec("DEL /F/Q \"$abs\"") : unlink($abs);
                }
            }

            closedir($dir);
            rmdir($path);
        }
    }

}

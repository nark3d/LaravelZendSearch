<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Laravel;

use Illuminate\Support\Facades\Config;
use BestServedCold\LaravelZendSearch\Laravel\Index;
use BestServedCold\LaravelZendSearch\TestCase;

final class IndexTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->index = new Index($this->app->config);
    }

    public function testContructor()
    {
        $path = $this->reflectionProperty($this->index, 'path');
        $this->assertTrue(is_string($path));
    }

}

<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Laravel;

use BestServedCold\LaravelZendSearch\Laravel\Filter;
use BestServedCold\LaravelZendSearch\Laravel\Index;
use BestServedCold\LaravelZendSearch\TestCase;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;
use Illuminate\Filesystem\Filesystem;

final class IndexTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->index = new Index(new Filter(new CaseInsensitive), $this->app->config, new Filesystem);

    }

    public function testContructor()
    {
        $path = $this->reflectionProperty($this->index, 'path');
        $this->assertTrue(is_string($path));
    }

}

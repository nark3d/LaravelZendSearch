<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use BestServedCold\LaravelZendSearch\Laravel\Index;
use BestServedCold\LaravelZendSearch\Laravel\Filter;
use BestServedCold\LaravelZendSearch\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;

class OptimiseTest extends TestCase
{
    public function testHandle()
    {
        $index = new Index(new Filter(new CaseInsensitive), $this->app->config, new Filesystem);
        $index->open($this->indexPath);

        Artisan::call('search:optimise');
        $this->assertEquals("Optimising search index.
Optimising finished.
", Artisan::output());
        Artisan::call('search:destroy');

    }
}

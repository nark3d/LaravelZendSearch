<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use BestServedCold\LaravelZendSearch\Laravel\Index;
use BestServedCold\LaravelZendSearch\Laravel\Filter;
use BestServedCold\LaravelZendSearch\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;

class DestroyTest extends TestCase
{
    public function testHandle()
    {
        Artisan::call('search:destroy');
        $this->assertEquals("Destroying the search index.
There was nothing to destroy?  Try a rebuild.
", Artisan::output());

        $index = new Index(new Filter(new CaseInsensitive), $this->app->config, new Filesystem);
        $index->open($this->indexPath);
        Artisan::call('search:destroy');
        $this->assertFalse(File::isDirectory($this->indexPath));
    }
}

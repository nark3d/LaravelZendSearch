<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use BestServedCold\LaravelZendSearch\Laravel\Index;
use BestServedCold\LaravelZendSearch\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class DestroyTest extends TestCase
{
    public function testHandle()
    {
        Artisan::call('search:destroy');
        $this->assertEquals("Destroying the search index.
There was nothing to destroy?  Try a rebuild.
", Artisan::output());

        $index = new Index($this->app->config);
        $index->open($this->indexPath);
        Artisan::call('search:destroy');
        $this->assertFalse(File::isDirectory($this->indexPath));
    }
}

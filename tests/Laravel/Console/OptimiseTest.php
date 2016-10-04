<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use BestServedCold\LaravelZendSearch\Laravel\Index;
use BestServedCold\LaravelZendSearch\TestCase;
use Illuminate\Support\Facades\Artisan;

class OptimiseTest extends TestCase
{
    public function testHandle()
    {
        $index = new Index($this->app->config);
        $index->open($this->indexPath);

        Artisan::call('search:optimise');
        $this->assertEquals("Optimising search index.
Optimising finished.
", Artisan::output());
        Artisan::call('search:destroy');

    }
}

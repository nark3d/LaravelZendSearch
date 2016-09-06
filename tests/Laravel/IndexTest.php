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

    public function testOpenNoConfig()
    {
        Config::set('search.index.path', false);
        $this->setExpectedException(\Exception::class);
        try {
            $this->index->open();
        } catch (\Exception $e) {
            $this->assertContains('No path specified nor config variable set.', $e->getMessage());
            throw $e;
        }
    }
    
    

}

<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Store;

use BestServedCold\LaravelZendSearch\TestCase;

class InsertTest extends TestCase
{
    private $insert;

    public function setUp()
    {
        parent::setUp();
        $this->insert = $this->app->make(Insert::class);
    }

    public function testInsert()
    {
        var_dump($this->insert->insert(1, ['some', 'shit']));
    }
}

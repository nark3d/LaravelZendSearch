<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Lucene\Store\Delete;
use BestServedCold\LaravelZendSearch\Lucene\Store\Insert;
use BestServedCold\LaravelZendSearch\TestCase;

class StoreTest extends TestCase
{
    private $store;

    public function setUp()
    {
        parent::setUp();
        $delete = $this->getMockBuilder(Delete::class)->disableOriginalConstructor()->getMock();
        $insert = $this->getMockBuilder(Insert::class)->disableOriginalConstructor()->getMock();
        $index = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $this->store = new Store($delete, $insert, $index);
    }

    public function testInsertModel()
    {
        $this->assertNull($this->store->insertModel(new DummyModel));
    }

    public function testDeleteModel()
    {
        $this->assertNull($this->store->deleteModel(new DummyModel()));
    }
}

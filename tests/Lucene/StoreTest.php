<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\Lucene\Store\Delete;
use BestServedCold\LaravelZendSearch\Lucene\Store\Insert;
use BestServedCold\LaravelZendSearch\TestCase;

class StoreTest extends TestCase
{
    private $store;

    public function setUp()
    {
        parent::setUp();
        $this->store = $this->app->make(Store::class);

        $delete = $this->getMockBuilder(Delete::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $delete->method('delete')->willReturnSelf();

        $insert = $this->getMockBuilder(Insert::class)
            ->disableOriginalConstructor()
            ->getMock();

        $insert->method('insert')->willReturnSelf();

        $this->store = new Store($delete, $insert);
    }

    public function testDelete()
    {
        $this->assertInstanceOf(Delete::class, $this->store->delete(1));
    }

    public function testInsert()
    {
        $this->assertInstanceOf(Insert::class, $this->store->insert(1, ['bob' => 'mary']));
    }

    public function testUid()
    {
        $this->store->uid('uniqueIdentifier');
        $this->assertEquals('uniqueIdentifier', $this->reflectionProperty($this->store, 'uid'));
    }
}

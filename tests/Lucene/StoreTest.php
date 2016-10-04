<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\Lucene\Store\Delete;
use BestServedCold\LaravelZendSearch\Lucene\Store\Insert;
use BestServedCold\LaravelZendSearch\TestCase;
use BestServedCold\LaravelZendSearch\Lucene\Index as LuceneIndex;

class StoreTest extends TestCase
{
    private $store;

    public function setUp()
    {
        parent::setUp();
        $this->store = $this->app->make(Store::class);

        $delete = $this->getMockBuilder(Delete::class)
            ->disableOriginalConstructor()
            ->getMock();
        $delete->method('delete')->willReturnSelf();

        $insert = $this->getMockBuilder(Insert::class)
            ->disableOriginalConstructor()
            ->getMock();

        $insert->method('insert')->willReturnSelf();

        $this->store = new Store($delete, $insert);
    }

    public function testDelete()
    {
        $index = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Delete::class, $this->store->delete($index, 1));
    }

    public function testInsert()
    {
        $index = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $luceneIndex = $this->getMockBuilder(LuceneIndex::class)->disableOriginalConstructor()->getMock();
        $luceneIndex->method('addDocument')->willReturn(null);
        $index->method('get')->willReturn($luceneIndex);
        $this->assertInstanceOf(Insert::class, $this->store->insert($index, 1, ['bob' => 'mary']));
    }
}

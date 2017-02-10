<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Store;

use BestServedCold\LaravelZendSearch\Lucene\Index;
use BestServedCold\LaravelZendSearch\Lucene\Search;
use BestServedCold\LaravelZendSearch\TestCase;
use ZendSearch\Lucene\Index as LuceneIndex;
use ZendSearch\Lucene\Document;

class InsertTest extends TestCase
{
    private $insert;
    private $index;

    public function setUp()
    {
        $this->index = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $luceneIndex = $this->getMockBuilder(LuceneIndex::class)->disableOriginalConstructor()->getMock();
        $luceneIndex->method('addDocument')->willReturn(null);
        $this->index->method('open')->willReturn($this->index);
        $this->index->method('get')->willReturn($luceneIndex);
        $document = $this->getMockBuilder(Document::class)->disableOriginalConstructor()->getMock();
        $this->insert = new Insert($document);
    }

    public function testInsert()
    {
        $this->assertNull($this->insert->insert($this->index, 1, ['some', 'shit'], 'someUid'));
    }

    public function testBoost()
    {
        $this->assertEquals(0.8, $this->reflectionMethod($this->insert, 'boost', ['value', ['value' => 0.8]]));
        $this->assertNull($this->reflectionMethod($this->insert, 'boost', ['bob', ['mary' => 0.8]]));
    }

    public function testGetLastInsert()
    {
        $this->assertInstanceOf(Document::class, Insert::getLastInsert());
    }
}

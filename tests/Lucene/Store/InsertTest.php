<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Store;

use BestServedCold\LaravelZendSearch\Lucene\Index;
use BestServedCold\LaravelZendSearch\Lucene\Search;
use BestServedCold\LaravelZendSearch\TestCase;
use ZendSearch\Lucene\Index as LuceneIndex;
use ZendSearch\Lucene\Document;

class InsertTest extends TestCase
{
    public function testInsert()
    {
        $index = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $luceneIndex = $this->getMockBuilder(LuceneIndex::class)->disableOriginalConstructor()->getMock();
        $luceneIndex->method('addDocument')->willReturn(null);
        $index->method('get')->willReturn($luceneIndex);
        $document = $this->getMockBuilder(Document::class)->disableOriginalConstructor()->getMock();
        $insert = new Insert($document);

        $this->assertNull($insert->insert($index, 1, ['some', 'shit'], 'someUid'));
    }
}

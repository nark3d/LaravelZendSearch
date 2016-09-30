<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Store;

use BestServedCold\LaravelZendSearch\Lucene\Index;
use BestServedCold\LaravelZendSearch\Lucene\Search;
use BestServedCold\LaravelZendSearch\TestCase;
use ZendSearch\Lucene\Index as LuceneIndex;

class DeleteTest extends TestCase
{
    public function testDelete()
    {
        $search = $this->getMockBuilder(Search::class)->disableOriginalConstructor()->getMock();
        $search->method('hits')->willReturn(['bob', 'mary']);
        $index = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $luceneIndex = $this->getMockBuilder(LuceneIndex::class)->disableOriginalConstructor()->getMock();
        $luceneIndex->method('addDocument')->willReturn(null);
        $index->method('get')->willReturn($luceneIndex);
        $delete = new Delete($search, $index);

        $this->assertInstanceOf(Delete::class, $delete->delete(1, 'someUid'));

    }
}

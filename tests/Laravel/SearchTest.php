<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use ZendSearch\Lucene\Index as LuceneIndex;
use BestServedCold\LaravelZendSearch\Lucene\Query;
use BestServedCold\LaravelZendSearch\TestCase;
use ZendSearch\Lucene\Search\Query\Boolean;
use ZendSearch\Lucene\Search\QueryHit;

class SearchTest extends TestCase
{
    private $search;

    public function setUp()
    {
        parent::setUp();
        $queryHit = $this->getMockBuilder(QueryHit::class)->disableOriginalConstructor()->getMock();
        $luceneIndex = $this->getMockBuilder(LuceneIndex::class)->disableOriginalConstructor()->getMock();
        $luceneIndex->method('find')->willReturn(['bob' => $queryHit]);

        $index = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $index->method('limit')->willReturnSelf();
        $index->method('open')->willReturnSelf();
        $index->method('get')->willReturn($luceneIndex);
        $query = new Query($this->getMock(Boolean::class));
        $this->search = new Search($index, $query);

        $this->search->model(new DummyModel);
    }

    public function testGet()
    {
        $this->assertEquals(['some' => 'data'], $this->search->get());
    }

    public function testFindId()
    {
        $this->assertInstanceOf(Search::class, $this->search->findId(1));
    }

    public function testFind()
    {
        $this->assertInstanceOf(Search::class, $this->search->find('bob'));
    }
}

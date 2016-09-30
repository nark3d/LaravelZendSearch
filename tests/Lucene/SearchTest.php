<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\TestCase;

use ZendSearch\Lucene\Search\Query\Boolean;
use ZendSearch\Lucene\Search\QueryHit;
use ZendSearch\Lucene\Index as LuceneIndex;

/**
 * Class SearchTest
 * @package BestServedCold\LaravelZendSearch\Lucene
 */
final class SearchTest extends TestCase
{
    /**
     * @var Search $search
     */
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
    }

    public function testHits()
    {
        $this->assertEquals(['bob' => null], $this->search->hits());
    }

    public function testOverloaderException()
    {
        $this->setExpectedException(\BadMethodCallException::class);

        try {
            $this->search->thisMethodDoesNotExistRandom();
        } catch (\BadMethodCallException $e) {
            throw $e;
        }

    }

    public function testOverloaderReachesQuery()
    {
        $mock = $this->getMock(
            Query::class,
            ['existingMethod'],
            [],
            'MockQuery',
            false
        );

        $mock
            ->expects($this->once())
            ->method('existingMethod')
            ->will($this->returnValue('something'));

        $search = new Search(new Index($this->app->config), $mock);

        $this->assertInstanceOf(Search::class, $search->existingMethod());
    }

    public function testLimit()
    {
        $this->search->limit(100);
        $this->assertAttributeEquals(100, 'limit', $this->search);
    }

    public function testPath()
    {
        $this->search->path('bobidy/bob/bob');
        $this->assertAttributeEquals('bobidy/bob/bob', 'path', $this->search);
    }

    public function testRaw()
    {
        $this->assertInstanceOf(Search::class, $this->search->raw('(+(somekeyword))'));
    }

    public function testTypes()
    {
        $this->assertInstanceOf(Search::class, $this->search->phrase('some phrase'));
        $this->assertInstanceOf(Search::class, $this->search->fuzzy('keywo'));
        $this->assertInstanceOf(Search::class, $this->search->wildcard('key*'));
        $this->assertInstanceOf(Search::class, $this->search->where('keyword'));
    }

    public function testMultiTerm()
    {
        $terms[] = $this->search->singleTerm('keyword');
        $terms[] = $this->search->singleTerm('keyword2');

        $this->assertInstanceOf(Search::class, $this->search->multiTerm($terms));
    }

    public function testMapWhereArray()
    {
        $whereArray = ['bob' => 'susan', 'harry' => 'sally', 'marge' => 'collin'];

        $this->assertSame(
            ['susan' => 'table', 'sally' => 'table', 'collin' => 'table'],
            $this->reflectionMethod($this->search, 'mapWhereArray', ['table', $whereArray])
        );
    }


    public function testMapIds()
    {
        $luceneIndex = $this->getMockBuilder(LuceneIndex::class)->disableOriginalConstructor()->getMock();

        $index = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();

        $index = new Index($this->indexPath);
        $index->setPath($this->indexPath); // @todo why doesn't the constructor do this?
        $index->open();

        $hit = new QueryHit($luceneIndex);
        $hit->id = 123;

        $array = ['bob' => $hit, 'harry' => $hit, 'marge' => $hit];

        $this->assertSame(
            ['bob' => 123, 'harry' => 123, 'marge' => 123],
            $this->reflectionMethod($this->search, 'mapIds', [$array])
        );
    }



}

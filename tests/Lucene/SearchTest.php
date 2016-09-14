<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\TestCase;

use ZendSearch\Lucene\Search\Query\Boolean;

final class SearchTest extends TestCase
{
    private $search;

    public function setUp()
    {
        parent::setUp();
        // PHP can't mock final classes!  Without using upopz, there's no other way of doing this.
        $index = new Index($this->app->config);
        $query = new Query($this->getMock(Boolean::class));
        $this->search = new Search($index, $query);
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

}

<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\TestCase;
use Illuminate\Support\Facades\Config;
use ZendSearch\Lucene\Lucene;
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
        $this->search->raw('(+(somekeyword))');

//        $this->assertAttributeInstanceOf('something', 'query', $this->search);
    }
}

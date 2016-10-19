<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\TestCase;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;
use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\Exception\RuntimeException;
use ZendSearch\Lucene\Index as LuceneIndex;

final class IndexTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->clearDirectory($this->indexPath);
    }

    public function testOpenNoPathException()
    {
        $this->setExpectedException(
            \Exception::class,
            'No path specified nor config variable set.'
        );

        $index = new Index(new Filter(new CaseInsensitive));
        $index->setPath(null);

        try {
            $index->open();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function testOpenNoForceException()
    {
        $this->setExpectedException(RuntimeException::class);

        $index = new Index(new Filter(new CaseInsensitive));
        try {
            $index->open($this->indexPath, false);
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function testOpenAndGet()
    {
        $index = new Index(new Filter(new CaseInsensitive));
        $index->open($this->indexPath);

        $this->assertInstanceOf(LuceneIndex::class, $index->get());
    }

    public function testLimit()
    {
        $index = new Index(new Filter(new CaseInsensitive));
        $index->limit(10);
        $this->assertSame(10, Lucene::getResultSetLimit());
    }
}

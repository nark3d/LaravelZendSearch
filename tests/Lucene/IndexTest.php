<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\TestCase;
use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\Exception\RuntimeException;
use ZendSearch\Lucene\Index as LuceneIndex;

final class IndexTest extends TestCase
{
    private $path =  "./tests/tmp/tempIndex";
    private $index;
    
    public function setUp()
    {
        parent::setUp();
        $this->index = new Index;
    }

    public function testOpenNoPathException()
    {
        $this->setExpectedException(\Exception::class, 'No path specified nor config variable set.');

        $this->index->setPath(null);

        try {
            $this->index->open();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function testOpenNoForceException()
    {
        $this->setExpectedException(RuntimeException::class);

        try {
            $this->index->open($this->path, false);
        } catch (RuntimeException $e) {
            throw $e;
        }

    }

    public function testOpen()
    {
        $this->_clearDirectory($this->path);

        $indexTwo = $this->index->open($this->path);
        $this->assertInstanceOf(Index::class, $indexTwo);

        $this->_clearDirectory($this->path);
    }

    public function testLimit()
    {
        $this->index->limit(10);
        $this->assertSame(10, Lucene::getResultSetLimit());
    }

    public function testGet()
    {
        $this->index->open($this->path);
        $this->assertInstanceOf(LuceneIndex::class, $this->index->get());

        $this->_clearDirectory($this->path);
    }

    private function _clearDirectory($path)
    {

        if (file_exists($path)) {
            $dir = opendir($path);
            while (($file = readdir($dir)) !== false) {
                if (!is_dir($path . '/' . $file)) {
                    @unlink($path . '/' . $file);
                }
            }
            closedir($dir);
            rmdir($path);
        }

    }
}

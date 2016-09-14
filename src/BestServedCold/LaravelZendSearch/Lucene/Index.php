<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use ZendSearch\Lucene\Analysis\Analyzer\Analyzer;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;
use ZendSearch\Lucene\Lucene;
use ZendSearch\Exception\ExceptionInterface;
use ZendSearch\Lucene\Index as LuceneIndex;
use ZendSearch\Lucene\SearchIndexInterface;

/**
 * Class Index
 * @package BestServedCold\LaravelZendSearch\Lucene
 */
class Index
{
    /**
     * @var LuceneIndex
     */
    private static $index;

    /**
     * @var string
     */
    protected $path;

    /**
     * Open
     *
     * @param  bool $path
     * @param  bool $forceCreate
     * @return $this
     * @throws ExceptionInterface
     * @throws \Exception
     */
    public function open($path = false, $forceCreate = true)
    {
        $path ? $this->path = $path : null;
        Analyzer::setDefault(new CaseInsensitive);

        self::$index = $this->index($this->path(), $forceCreate);
        return $this;
    }

    /**
     * @param integer $limit
     * @return $this
     */
    public function limit($limit)
    {
        Lucene::setResultSetLimit($limit);
        return $this;
    }

    /**
     * Index
     *
     * Extends the Lucene "open" method to create the index if it doesn't exist.
     *
     * @param  string|boolean $path
     * @param  boolean        $forceCreate
     * @return SearchIndexInterface
     * @throws \Exception
     */
    private function index($path, $forceCreate = true)
    {
        try {
            $index = Lucene::open($path);
        } catch (ExceptionInterface $error) {
            if ($forceCreate) {
                $index = Lucene::create($path);
            } else {
                throw $error;
            }
        }
        
        return $index;
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function path()
    {
        if (!$this->path) {
            throw new \Exception('No path specified nor config variable set.');
        }

        return $this->path;
    }

    /**
     * @return LuceneIndex
     */
    public function get()
    {
        return self::$index;
    }
}

<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

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
    private $index;

    /**
     * @var string|boolean $path
     */
    protected $path;

    protected $filter;

    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Open
     *
     * @param  string|boolean $path
     * @param  bool           $forceCreate
     * @return $this
     * @throws ExceptionInterface
     * @throws \Exception
     */
    public function open($path = false, $forceCreate = true)
    {
        $this->path = $path ? $path : $this->path;
        $this->index = $this->createIndex($this->path(), $forceCreate);
        $this->filter->setFilters();
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
     * Create Index
     *
     * Extends the Lucene "open" method to create the index if it doesn't exist.
     *
     * @param  string|boolean $path
     * @param  boolean        $forceCreate
     * @return SearchIndexInterface
     * @throws \Exception
     */
    private function createIndex($path, $forceCreate = true)
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
        return $this->index;
    }
}

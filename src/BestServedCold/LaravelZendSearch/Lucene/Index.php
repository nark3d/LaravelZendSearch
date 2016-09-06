<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use ZendSearch\Lucene\Analysis\Analyzer\Analyzer;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;
use ZendSearch\Lucene\Lucene;
use ZendSearch\Exception\ExceptionInterface;
use ZendSearch\Lucene\Index as LuceneIndex;

class Index
{
    /**
     * @var LuceneIndex
     */
    private $index;

    /**
     * @var string
     */
    protected $path;

    /**
     * Open
     *
     * @param bool $path
     * @param bool $forceCreate
     * @return \ZendSearch\Lucene\SearchIndexInterface
     * @throws ExceptionInterface
     * @throws \Exception
     */
    public function open($path = false, $forceCreate = true)
    {
        Analyzer::setDefault(new CaseInsensitive);
        $this->index = $this->index($this->path($path), $forceCreate);
        return $this->index;
    }

    /**
     * @param $limit
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
     * @return LuceneIndex
     * @throws ExceptionInterface
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
     * @param  bool $path
     * @return string
     * @throws \Exception
     */
    protected function path($path = false)
    {
        if (! $path) {
            throw new \Exception('No path specified nor config variable set.');
        }

        return $path;
    }

    /**
     * @return LuceneIndex
     */
    public function get()
    {
        return $this->index;
    }
}

<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Lucene\Query;
use BestServedCold\LaravelZendSearch\Lucene\Search as LuceneSearch;

/**
 * Class Search
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
class Search extends LuceneSearch
{
    use EloquentTrait;

    /**
     * Search constructor.
     * @param Index $index
     * @param Query $query
     */
    public function __construct(Index $index, Query $query)
    {
        parent::__construct($index, $query);
        $this->path(config('search.index.path'));
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->model->whereIn($this->key, $this->hits())->get();
    }

    /**
     * @param $id
     * @return $this
     */
    public function findId($id)
    {
        $this->where($id, 'xref_id');
        return $this;
    }

    /**
     * @return mixed
     */
    public function hits()
    {
        $this->where($this->uid, 'uid');
        return parent::hits();
    }

    /**
     * @param $string
     * @todo  This needs to be a find all with all the search attributes.
     * @return $this
     */
    public function find($string)
    {
        $this->where($string);
        return $this;
    }
}


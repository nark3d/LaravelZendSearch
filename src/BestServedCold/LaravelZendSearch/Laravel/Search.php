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

    private $hits = [];

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
        return $this->model->whereIn(
            $this->key,
            $this->hits()
        )->get();
    }

    /**
     * @param $id
     * @return $this
     */
    public function findId($id)
    {
        $this->match($id, 'xref_id');
        return $this;
    }

    /**
     * Hits
     *
     * We store the hits, no point in running the query twice.  This also allows us to set the UID once for the model
     * table name.  A new search instance is required to run against another model.
     *
     * @return mixed
     */
    public function hits()
    {
        if (!empty($this->hits)) {
            return $this->hits;
        }

        $this->match(base64_encode($this->uid), 'uid');

        $this->hits = parent::hits();
        return $this->hits;
    }

    /**
     * @param $string
     * @return $this
     */
    public function find($string)
    {
        $this->where($string);
        return $this;
    }
}

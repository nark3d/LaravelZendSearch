<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Laravel\Eloquent\EloquentTrait;
use BestServedCold\LaravelZendSearch\Lucene\Query;
use BestServedCold\LaravelZendSearch\Lucene\Search as LuceneSearch;

class Search extends LuceneSearch
{
    use EloquentTrait;

    public function __construct(Index $index, Query $query)
    {
        parent::__construct($index, $query);
        $this->path(config('search.index.path'));
    }

    public function get()
    {
        return $this->model->whereIn($this->key, $this->hits())->get();
    }

    public function findId($id)
    {
        $this->where($id, 'xref_id');
    }

    public function hits()
    {
        $this->where($this->uid, 'uid');
        return parent::hits();
    }

    /**
     * @param $string
     * @todo  This needs to be a find all with all the search attributes.
     */
    public function find($string)
    {

    }
}

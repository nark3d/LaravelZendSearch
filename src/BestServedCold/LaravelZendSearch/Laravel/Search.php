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
    }
}


<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Laravel\Eloquent\EloquentTrait;
use BestServedCold\LaravelZendSearch\Lucene\Store as LuceneStore;

final class Store extends LuceneStore
{
    use EloquentTrait;

}

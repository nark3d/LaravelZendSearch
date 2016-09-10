<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Lucene\Index as LuceneIndex;
use Illuminate\Config\Repository;

final class Index extends LuceneIndex
{
    public function __construct(Repository $config)
    {
        $this->setPath($config->get('search.index.path'));
        $this->open();
    }
}

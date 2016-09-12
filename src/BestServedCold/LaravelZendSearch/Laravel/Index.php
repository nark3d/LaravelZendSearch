<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Lucene\Index as LuceneIndex;
use Illuminate\Config\Repository;

/**
 * Class Index
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
final class Index extends LuceneIndex
{
    /**
     * Index constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->setPath($config->get('search.index.path'));
        $this->open();
    }
}

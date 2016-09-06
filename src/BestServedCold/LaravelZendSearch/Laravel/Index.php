<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Lucene\Index as LuceneIndex;
use Illuminate\Config\Repository;

final class Index extends LuceneIndex
{
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Path
     *
     * If the path doesn't exist, then get it from the configuration file. If that fails, throw an exception.
     *
     * @param  bool $path
     * @return bool
     * @throws \Exception
     */
    protected function path($path = false)
    {
        $path = $path ?: $this->config->get('search.index.path');
        return parent::path($path);
        
    }
}

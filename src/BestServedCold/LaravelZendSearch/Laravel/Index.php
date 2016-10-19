<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Lucene\Index as LuceneIndex;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Index
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
class Index extends LuceneIndex
{
    /**
     * Index constructor.
     * @param Repository $config
     */
    public function __construct(Filter $filter, Repository $config, Filesystem $filesystem)
    {
        $this->setPath($config->get('search.index.path'));
        $this->addFilters($config->get('search.filters'), $filesystem);

        parent::__construct($filter);
    }

    /**
     * @param array $filters
     */
    private function addFilters(array $filters = [], Filesystem $filesystem)
    {
        foreach ($filters as $filter => $switch) {
            $switch === false ? null : $this->handleFilter($switch, $filter, $filesystem);
        }
    }

    /**
     * @param $switch
     * @param $filter
     * @param Filesystem $filesystem
     */
    private function handleFilter($switch, $filter, Filesystem $filesystem)
    {
        $filter === 'StopWords' && !is_array($switch)
            ? $this->stopWordFilter($filter, $switch, $filesystem)
            : $this->filter($filter, $switch);
    }

    /**
     * @param $filter
     * @param $switch
     */
    private function filter($filter, $switch)
    {
        Filter::addFilter($filter, $switch === true ? [] : [$switch]);
    }

    /**
     * @param $switch
     * @param Filesystem $filesystem
     */
    private function stopWordFilter($switch, Filesystem $filesystem)
    {
        Filter::addStopWordFilter($switch === true ? 'en' : $switch, $filesystem);
    }
}

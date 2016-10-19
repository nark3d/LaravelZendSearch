<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Lucene\Filter as LuceneFilter;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Filter
 *
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
class Filter extends LuceneFilter
{
    /**
     * @param  string     $language
     * @param  Filesystem $filesystem
     * @return void
     */
    public static function addStopWordFilter($language = 'en', Filesystem $filesystem)
    {
        self::addFilter(
            'StopWords',
            $filesystem->getRequire(
                __DIR__ .
                DIRECTORY_SEPARATOR .
                'Filter' .
                DIRECTORY_SEPARATOR .
                'StopWord' .
                DIRECTORY_SEPARATOR .
                $language .
                '.php'
            )
        );

    }
}

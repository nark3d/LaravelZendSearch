<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Filter;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use ZendSearch\Lucene\Analysis\TokenFilter\LowerCase;
use ZendSearch\Lucene\Analysis\TokenFilter\LowerCaseUtf8;
use ZendSearch\Lucene\Analysis\TokenFilter\ShortWords;
use ZendSearch\Lucene\Analysis\TokenFilter\StopWords;
use ZendSearch\Lucene\Analysis\TokenFilter\TokenFilterInterface;

/**
 * Class ShortWord
 * @package BestServedCold\LaravelZendSearch\Lucene\Filter
 */
class Factory
{
    /**
     * @param  string $filter
     * @param  mixed  $option
     * @return TokenFilterInterface
     * @throws \InvalidArgumentException
     */
    public static function getFilter($filter, $option)
    {
        $supportedFilters = self::getSupportedFilters();
        self::supportedException($filter, $supportedFilters);
        return new $supportedFilters[$filter]($option);
    }

    /**
     * @param  string $filter
     * @param  array  $supportedFilters
     * @throws \InvalidArgumentException
     */
    private static function supportedException($filter, array $supportedFilters)
    {
        if (!array_key_exists($filter, $supportedFilters)) {
            throw new \InvalidArgumentException(
                'Filter ['.$filter.'] is not supported by ['.self::class.']'
            );
        }
    }

    /**
     * @return array
     */
    private static function getSupportedFilters()
    {
        return [
            'LowerCase'     => LowerCase::class,
            'LowerCaseUtf8' => LowerCaseUtf8::class,
            'ShortWords'    => ShortWords::class,
            'StopWords'     => StopWords::class
        ];
    }
}

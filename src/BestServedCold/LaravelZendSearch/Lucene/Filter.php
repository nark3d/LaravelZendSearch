<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\Lucene\Filter\Factory;
use ZendSearch\Lucene\Analysis\Analyzer\Analyzer;
use ZendSearch\Lucene\Analysis\Analyzer\Common\AbstractCommon;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;
use ZendSearch\Lucene\Search\QueryParser;

/**
 * Class Filter
 *
 * @package BestServedCold\LaravelZendSearch\Lucene
 */
class Filter
{
    /**
     * @var null|AbstractCommon
     */
    private static $analyzer = null;

    /**
     * @var array
     */
    private static $filters = [];

    /**
     * Filter constructor.
     *
     * @param CaseInsensitive $caseInsensitive
     */
    public function __construct(CaseInsensitive $caseInsensitive)
    {
        self::$analyzer = self::$analyzer ?: $caseInsensitive;
    }

    /**
     * @param AbstractCommon $analyzer
     */
    public static function setAnalyzer(AbstractCommon $analyzer)
    {
        self::$analyzer = $analyzer;
    }

    /**
     * @return null|AbstractCommon|CaseInsensitive
     */
    public static function getAnalyzer()
    {
        return self::$analyzer;
    }

    /**
     * @param string $filter
     * @param mixed  $option
     */
    public static function addFilter($filter, $option)
    {
        if (self::$analyzer === null) {
            self::$analyzer = new CaseInsensitive;
        }

        if (!in_array($filter, self::$filters)) {
            self::$analyzer->addFilter(Factory::getFilter($filter, $option));
            self::$filters[] = $filter;
        }

    }

    /**
     * @return void
     */
    public function setFilters()
    {
        QueryParser::setDefaultEncoding('UTF-8');
        if (self::$analyzer instanceof AbstractCommon) {
            Analyzer::setDefault(self::$analyzer);
        }

    }
}

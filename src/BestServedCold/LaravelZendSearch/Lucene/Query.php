<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use ZendSearch\Lucene\Search\Query\Boolean as LuceneBoolean;
use ZendSearch\Lucene\Search\Query\AbstractQuery;

/**
 * Class Query
 *
 * @package BestServedCold\LaravelZendSearch\Lucene
 */
final class Query
{
    /**
     * @var bool
     */
    private $sign = true;

    /**
     * @var LuceneBoolean
     */
    private $boolean;

    /**
     * Query constructor.
     *
     * @param LuceneBoolean $boolean
     */
    public function __construct(LuceneBoolean $boolean)
    {
        $this->boolean = $boolean;
    }

    /**
     * Add
     *
     * @param  AbstractQuery $query
     * @return $this
     */
    public function add(AbstractQuery $query)
    {
        $this->boolean->addSubquery($query, $this->sign);
        $this->sign = true;
        return $this;
    }

    /**
     * Required
     *
     * @return $this
     */
    public function required()
    {
        $this->sign = true;
        return $this;
    }

    /**
     * Optional
     *
     * @return $this
     */
    public function optional()
    {
        $this->sign = null;
        return $this;
    }

    /**
     * Prohibited
     *
     * @return $this
     */
    public function prohibited()
    {
        $this->sign = false;
        return $this;
    }

    /**
     * Get Boolean
     *
     * @return LuceneBoolean
     */
    public function getBoolean()
    {
        return $this->boolean;
    }

    /**
     * Get Sign
     *
     * @return bool
     */
    public function getSign()
    {
        return $this->sign;
    }
}

<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\Search\Wildcard;
use ZendSearch\Lucene\Index\Term;
use ZendSearch\Lucene\Search\Query\AbstractQuery;
use ZendSearch\Lucene\Search\Query\Fuzzy;
use ZendSearch\Lucene\Search\Query\MultiTerm;
use ZendSearch\Lucene\Search\Query\Phrase;
use ZendSearch\Lucene\Search\Query\Term as QueryTerm;
use ZendSearch\Lucene\Search\QueryParser;

class Search
{
    protected $index;
    protected $query;
    private $path;
    private $limit = 25;

    /**
     * Search constructor.
     * @param Index $index
     * @param \BestServedCold\LaravelZendSearch\Query $query
     */
    public function __construct(Index $index, Query $query)
    {
        $this->index = $index;
        $this->query = $query;
    }

    /**
     * @param  $name
     * @param  $arguments
     * @return $this
     * @throws \BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->$name($arguments);
        }

        if (method_exists($this->query, $name)) {
            $this->query->$name($arguments);
            return $this;
        }

        throw new \BadMethodCallException;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param bool $path
     * @return $this
     */
    public function path($path = false)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param $string
     */
    public function raw($string)
    {
        $this->query->add(QueryParser::parse($string));
        return $this;
    }

    /**
     * @param $string
     * @param bool $field
     */
    public function phrase($string, $field = false, $offsets = null)
    {
        $this->query->add(new Phrase(explode(' ', $string), $offsets, $field));
        return $this;

    }

    /**
     * @param $string
     * @param bool $field
     */
    public function fuzzy($string, $field = false)
    {
        $this->query->add(new Fuzzy($this->term($field, $string)));
        return $this;
    }

    /**
     * @param $string
     * @param bool $field
     * @return Term
     */
    protected function term($string, $field = false)
    {
        return new Term(strtoupper($string), $field);
    }

    /**
     * @param $string
     * @param bool $field
     * @param array $options
     */
    public function wildcard($string, $field = false, $options = [])
    {
        $this->query->add((new Wildcard($field, $string, $options))->get());
    }

    /**
     * @param $string
     * @param array|bool|string $field
     * @todo  Work out why the search only works if the string is uppercase...
     * @return $this|bool
     */
    public function where($string, $field = false)
    {
        is_array($field)
            ? $this->multiTerm($this->mapWhereArray($string, $field))
            : $this->query->add($this->singleTerm($string, $field));

        var_dump($this->query->getBoolean()->__toString());
        return $this;
    }

    /**
     * @param array $terms
     * @return $this
     */
    public function multiTerm(array $terms)
    {
        $multiTerm = new MultiTerm;
        foreach ($terms as $field => $value) {
            $multiTerm->addTerm($this->term($value, $field), $this->query->getSign());
        }

        $this->query->add($multiTerm);

        return $this;
    }

    /**
     * @param $string
     * @param array $array
     * @return mixed
     * @todo abstract this out
     */
    private function mapWhereArray($string, array $array)
    {
        return array_map(function () use ($string) {
            return $string;
        }, array_flip($array));
    }

    /**
     * @param $string
     * @param $field
     * @return QueryTerm
     */
    public function singleTerm($string, $field = false)
    {
        return new QueryTerm($this->term($string, $field));
    }

    /**
     * @return mixed
     */
    public function hits()
    {
        return $this->mapIds($this->index->limit($this->limit)->open($this->path)->find($this->query->getBoolean()));
    }

    /**
     * @param array $array
     * @return mixed
     * @todo abstract this out
     */
    private function mapIds(array $array)
    {
        return array_map(function ($v) {
            return $v->id;
        }, $array);
    }
}

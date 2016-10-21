<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use ZendSearch\Lucene\Index\Term;
use ZendSearch\Lucene\Search\Query\Fuzzy;
use ZendSearch\Lucene\Search\Query\MultiTerm;
use ZendSearch\Lucene\Search\Query\Phrase;
use ZendSearch\Lucene\Search\Query\Wildcard;
use ZendSearch\Lucene\Search\Query\Term as QueryTerm;
use ZendSearch\Lucene\Search\QueryHit;
use ZendSearch\Lucene\Search\QueryParser;
use ZendSearch\Lucene\Search\Query\Boolean as LuceneBoolean;

/**
 * Class Search
 * @package BestServedCold\LaravelZendSearch\Lucene
 */
class Search
{
    /**
     * @var Index $index
     */
    protected $index;

    /**
     * @var Query $query
     */
    protected $query;

    /**
     * @var string|boolean $path
     */
    private $path;

    /**
     * @var int $limit
     */
    private $limit = 25;

    /**
     * @var int $offset
     */
    private $offset = 0;

    /**
     * @var LuceneBoolean
     */
    private static $boolean;

    /**
     * Search constructor.
     *
     * @param Index $index
     * @param Query $query
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
        if (method_exists($this->query, $name)) {
            $this->query->$name($arguments);
            return $this;
        }

        throw new \BadMethodCallException;
    }

    /**
     * @param  integer $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param  integer $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param bool|string $path
     * @return $this
     */
    public function path($path = false)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param $string
     * @return $this
     */
    public function raw($string)
    {
        $this->query->add(QueryParser::parse($this->prepareString($string)));
        return $this;
    }

    /**
     * Prepare String
     *
     * We want to keep everything clean and lowercase here.  If you really want case sensitive
     * pattern matching, fork and code it...
     *
     * @param $string
     * @return string
     */
    private function prepareString($string)
    {
        return strtolower(strip_tags($string));
    }

    /**
     * @param $string
     * @param null|string $field
     * @param null        $offsets
     * @return $this
     * @return $this
     */
    public function phrase($string, $field = null, $offsets = null)
    {
        $this->query->add(new Phrase(explode(' ', $this->prepareString($string)), $offsets, $field));
        return $this;
    }

    /**
     * @param $string
     * @param null|string   $field
     * @return $this
     */
    public function fuzzy($string, $field = null)
    {
        $this->query->add(new Fuzzy($this->indexTerm($field, $string)));
        return $this;
    }

    /**
     * @param $string
     * @param null|string   $field
     * @return Term
     */
    protected function indexTerm($string, $field = null)
    {
        return new Term($this->prepareString($string), $field);
    }

    /**
     * @param  $string
     * @param  null|string $field
     * @return $this
     */
    public function wildcard($string, $field = null)
    {
        $this->query->add(new Wildcard($this->indexTerm($string, $field)));
        return $this;
    }

    /**
     * Where
     *
     * A helper method to access phrase or to pass multiple fields.  Phrase doesn't "match" exactly and
     * allows searching within the text field rather than matching the whole string.
     *
     * @param boolean|$string
     * @param null|string $field
     * @return $this|bool
     */
    public function where($string, $field = null)
    {
        is_array($field)
            ? $this->multiTerm($this->mapWhereArray($string, $field))
            : $this->phrase($string, $field);

        return $this;
    }

    /**
     * Match
     *
     * Provides an exact pattern match.
     *
     * @param  $string
     * @param  string $field
     * @return $this
     */
    public function match($string, $field = null)
    {
        $this->query->add($this->term($string, $field));
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
            $multiTerm->addTerm($this->indexTerm($value, $field), null);
        }

        $this->query->add($multiTerm);

        return $this;
    }

    /**
     * @param string $string
     * @param array  $array
     * @return mixed
     */
    private function mapWhereArray($string, array $array)
    {
        return array_map(
            function() use ($string) {
                return $string;
            },
            array_flip($array)
        );
    }

    /**
     * @param string         $string
     * @param string|null $field
     * @return QueryTerm
     */
    public function term($string, $field = null)
    {
        return new QueryTerm($this->indexTerm($string, $field));
    }

    /**
     * @return mixed
     */
    public function hits()
    {
        $index = $this->index->limit($this->limit + $this->offset)->open($this->path)->get();
        self::$boolean = $this->query->getBoolean();
        return $this->slice($this->mapIds($index->find(self::$boolean)));
    }

    /**
     * Slice
     *
     * This may look nasty, but there really isn't another way of offsetting results Lucene, and nor would
     * you want to really, that's not what it's designed for.  It will be quick up to a thousand and after
     * that you should be asking the user to "refine their search".
     *
     * @param  mixed $hits
     * @return array
     */
    public function slice($hits)
    {
        return $this->offset ? array_slice($hits, $this->offset) : $hits;
    }

    /**
     * @param  array|QueryHit $array
     * @return mixed
     */
    private function mapIds($array)
    {
        return array_map(
            function(QueryHit $hit) {
                return isset($hit->xref_id) ? $hit->xref_id : null;
            },
            $array
        );
    }

    /**
     * @return LuceneBoolean
     */
    public static function getLastQuery()
    {
        return self::$boolean;
    }
}

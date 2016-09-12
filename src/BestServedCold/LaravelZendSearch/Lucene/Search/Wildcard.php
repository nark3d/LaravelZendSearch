<?php 

namespace BestServedCold\LaravelZendSearch\Search;

use ZendSearch\Lucene\Index\Term;
use ZendSearch\Lucene\Search\Query\Wildcard as Type;

/**
 * Class Wildcard
 * @package BestServedCold\LaravelZendSearch\Search
 */
final class Wildcard extends AbstractType implements TypeInterface
{
    /**
     * @return Type
     */
    public function get()
    {
        return new Type(new Term($this->string, $this->field));
    }
}

<?php 

namespace BestServedCold\LaravelZendSearch\Search;

use ZendSearch\Lucene\Index\Term;
use ZendSearch\Lucene\Search\Query\Wildcard as Type;

final class Wildcard extends AbstractType implements SearchInterface
{
    public function get()
    {
        return new Type(new Term($this->string, $this->field));
    }
}

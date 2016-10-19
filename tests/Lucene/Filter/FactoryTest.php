<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Filter;

use BestServedCold\LaravelZendSearch\TestCase;

class FactoryTest extends TestCase
{
    public function testSupportedException()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            'Filter [thisIsNotAFilter] is not supported by [BestServedCold\LaravelZendSearch\Lucene\Filter\Factory]'
        );

        Factory::getFilter('thisIsNotAFilter', 'anOption');
    }
}

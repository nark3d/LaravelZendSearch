<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\TestCase;
use ZendSearch\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8\CaseInsensitive as Utf8CaseInsensitive;

class FilterTest extends TestCase
{
    public function testAddFilterSetsDefaultAnaylzer()
    {
        $reflection = new \ReflectionClass(Filter::class);
        $reflectionAnalyzer = $reflection->getProperty('analyzer');
        $reflectionAnalyzer->setAccessible(true);
        $reflectionAnalyzer->setValue(null);
        $this->assertNull(Filter::getAnalyzer());
        Filter::addFilter('ShortWords', 3);
        $this->assertInstanceOf(CaseInsensitive::class, Filter::getAnalyzer());
        $this->assertNotNull(Filter::getAnalyzer());
    }

    public function testSetAnalyzer()
    {
        Filter::setAnalyzer(new Utf8CaseInsensitive);
        $this->assertInstanceOf(Utf8CaseInsensitive::class, Filter::getAnalyzer());
    }
}

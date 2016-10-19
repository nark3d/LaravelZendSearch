<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\TestCase;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;

use ZendSearch\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive as TextNumCaseInsensitive;

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
        Filter::setAnalyzer(new TextNumCaseInsensitive);
        $this->assertInstanceOf(TextNumCaseInsensitive::class, Filter::getAnalyzer());
    }
}

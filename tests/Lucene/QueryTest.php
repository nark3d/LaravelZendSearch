<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\TestCase;
use Illuminate\Support\Facades\App;
use ZendSearch\Lucene\Search\Query\Boolean;

/**
 * Class QueryTest
 * @package BestServedCold\LaravelZendSearch\Lucene
 */
class QueryTest extends TestCase
{
    private $query;

    public function setUp()
    {
        parent::setUp();
        $this->query = App::make(Query::class);

    }

    public function testRequired()
    {
        $this->query->required();
        $this->assertSame(true, $this->query->getSign());
    }

    public function testOptional()
    {
        $this->query->optional();
        $this->assertSame(null, $this->query->getSign());
    }

    public function testProhibited()
    {
        $this->query->prohibited();
        $this->assertSame(false, $this->query->getSign());
    }

    public function testGetBoolean()
    {
        $this->assertInstanceOf(Boolean::class, $this->query->getBoolean());
    }
}

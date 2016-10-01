<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\TestCase;

class SearchTraitTest extends TestCase
{
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = new DummyModel();
    }

    public function testSetSearchFields()
    {
        DummyModel::setSearchFields(['bob', 'mary']);
        $this->assertEquals(['bob', 'mary'], DummyModel::getSearchFields());
    }

    public function testSearch()
    {
        $this->assertInstanceOf(Search::class, DummyModel::search());
    }

    public function testSaved()
    {
        $this->assertNull(DummyModel::bootSearchTrait());
    }

}

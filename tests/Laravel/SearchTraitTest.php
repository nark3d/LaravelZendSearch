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

    private $modelSet;
    private $insertModelSet;
    private $deleteModelSet;

    /**
     * I wasn't sure how to do closure testing like this, so I've hacked it a little bit.  But, hey, that's what
     * unit testing is all about right?
     */
    public function testInsertCallback()
    {
        $model = new DummyModel;
        $store = $this->getMockBuilder(Store::class)->disableOriginalConstructor()->getMock();

        $store->method('model')->willReturnCallback(function() {
            $this->modelSet = true;

        });
        $store->method('insertModel')->willReturnCallback(function() {
            $this->insertModelSet = true;
        });
        $store->method('deleteModel')->willReturnCallback(function() {
            $this->deleteModelSet = true;
        });

        $insertCallback = $this->reflectionMethod($model, 'insertCallback', [$store]);

        $insertCallback($model);
        $this->assertInstanceOf(\Closure::class, $insertCallback);
        $this->assertTrue($this->modelSet);
        $this->assertTrue($this->insertModelSet);

        $this->modelSet = false;

        $deleteCallback = $this->reflectionMethod($model, 'deleteCallback', [$store]);
        $deleteCallback($model);
        $this->assertInstanceOf(\Closure::class, $deleteCallback);
        $this->assertTrue($this->modelSet);
        $this->assertTrue($this->deleteModelSet);
    }

    public function testNotAModel()
    {
        $this->setExpectedException(
            \Exception::class,
            'SearchTrait must only be used with Eloquent models, [BestServedCold\LaravelZendSearch\Laravel\NotAModel] used.'
        );

        $notAModel = new NotAModel;
        $notAModel->search();
    }

    public function testNoSearchFieldsMethod()
    {
        $this->setExpectedException(
            \Exception::class,
            'Method [searchFields] must exist and be static.'
        );
        $searchTrait = $this->getMockBuilder(SearchTrait::class)->getMockForTrait();
        $this->reflectionMethod($searchTrait, 'searchFields');

    }
}

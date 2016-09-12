<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\TestCase;

class EloquentTraitTest extends TestCase
{
    use EloquentTrait;

    public function testModel()
    {
        $model = new DummyModel;
        $this->model($model);

        $this->assertEquals('different', $this->key);
        $this->assertEquals('dummy_models', $this->uid);
        $this->assertEquals($model, $this->model);
    }
}

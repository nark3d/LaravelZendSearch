<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Database\Eloquent\Model;

trait EloquentTrait
{
    protected $key = 'id';
    protected $model;
    protected $uid;

    public function model(Model $model)
    {
        $this->model = $model;
        $this->key = $model->getKeyName();
        $this->uid = $model->getTable();
        return $this;
    }
}

<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentTrait
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
trait EloquentTrait
{
    protected $key = 'id';
    protected $model;
    protected $uid;

    /**
     * @param Model $model
     * @return $this
     */
    public function model(Model $model)
    {
        $this->model = $model;
        $this->key = $model->getKeyName();
        $this->uid = $model->getTable();
        return $this;
    }
}

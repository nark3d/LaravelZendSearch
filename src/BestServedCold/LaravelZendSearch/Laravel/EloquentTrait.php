<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentTrait
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
trait EloquentTrait
{
    /**
     * @var string $key
     */
    protected $key = 'id';

    /**
     * @var Model $model
     */
    protected $model;

    /**
     * @var
     */
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

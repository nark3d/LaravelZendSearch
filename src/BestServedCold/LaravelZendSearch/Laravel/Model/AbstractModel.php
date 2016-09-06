<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Model;

use BestServedCold\LaravelZendSearch\Laravel\Eloquent;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel
{
    protected $model;
    protected $table;
    protected $primaryKey;
    protected $eloquent;

    public function __construct(Model $model, Eloquent $eloquent)
    {
        $this->model = $model;
        $this->table = $model->getTable();
        $this->primaryKey = $model->getKeyName();
        $this->eloquent = $eloquent;
    }
}

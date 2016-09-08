<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Eloquent;

use Illuminate\Database\Eloquent\Model;

trait EloquentTrait
{
    protected $table;
    protected $model;
    protected $key = 'id';

    public function model(Model $model)
    {
        $this->table = $model->getTable();
        $this->key = $model->getKey();
        return $this;
    }

    public function modelName($modelName)
    {
        if (! class_exists($modelName) || ! $modelName instanceof Model) {
            // @todo you need to get better at exceptions...
            throw new \Exception;
        }

        return $this->model(new $modelName);
    }

    public function key($key)
    {
        $this->key = $key;
    }

    public function table($table)
    {
        $this->table = $table;
    }

    protected function checkForTable()
    {
        if (! $this->table) {
            // @todo you need to get better at exceptions...
            throw new \Exception('No table name set');
        }
    }


    // @todo attributes, config, etc.
}

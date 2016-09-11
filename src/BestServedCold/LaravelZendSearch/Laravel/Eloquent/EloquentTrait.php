<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Eloquent;

use Illuminate\Database\Eloquent\Model;

trait EloquentTrait
{
    protected $table;
    protected $model;
    protected $key = 'id';
    protected $uid;

    public function model(Model $model)
    {
        $this->model = $model;
        $this->table($model->getTable());
        $this->key($model->getKeyName());
        $this->uid = $this->table;
        if (isset($this->query)) {
            $this->uid($this->table);
        }
        return $this;
    }

    /**
     * Uid
     *
     * @param $uid
     * @return $this
     */
    public function uid($uid)
    {
        $this->where('uid', $uid);
        return $this;
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

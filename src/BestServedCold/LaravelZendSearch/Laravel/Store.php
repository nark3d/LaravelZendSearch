<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Lucene\Store as LuceneStore;
use Illuminate\Database\Eloquent\Model;
use BestServedCold\LaravelZendSearch\Lucene\Store\Delete;
use BestServedCold\LaravelZendSearch\Lucene\Store\Insert;

/**
 * Class Store
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
class Store extends LuceneStore
{
    use EloquentTrait;

    private $index;

    public function __construct(Delete $delete, Insert $insert, Index $index)
    {
        parent::__construct($delete, $insert);
        $this->index = $index;
    }

    /**
     * @param Model $model
     */
    public function insertModel(Model $model, $deleteFirst = true)
    {
        $this->index->open();
        $this->model($model);
        return $this->insert(
            $model->id,
            $this->filterFields($model),
            $this->uid,
            $deleteFirst
        );
    }

    /**
     * @param Model $model
     */
    public function deleteModel(Model $model)
    {
        $this->delete($model->id, $this->uid);
    }

    /**
     * @param Model $model
     * @return array
     */
    private function filterFields($model)
    {
        return $this->filterKeysFromArray($model->attributesToArray(), $model::getSearchFields());
    }

    /**
     * @param  array $haystack
     * @param  array $needle
     * @return array
     */
    private function filterKeysFromArray(array $haystack, array $needle)
    {
        return array_intersect_key($haystack, array_flip($needle));
    }
}

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
final class Store extends LuceneStore
{
    use EloquentTrait;

    public function __construct(Delete $delete, Insert $insert, Index $index)
    {
        parent::__construct($delete, $insert, $index);
    }

    /**
     * @param Model $model
     */
    public function insertModel(Model $model, $deleteFirst = true)
    {
        return $this->insert(
            $model->id,
            $this->filterFields($model),
            $this->filterParameters($model),
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
     * @param Model $model
     * @return array
     */
    private function filterParameters($model)
    {
        if (!empty($model::getSearchParameters())) {
            return $this->filterKeysFromArray($model->attributesToArray(), $model::getSearchParameters());
        }

        return [ ];
    }

    /**
     * @param  array $haystack
     * @param  array $needle
     * @return array
     * @todo   refactor this out of here.
     */
    private function filterKeysFromArray(array $haystack, array $needle)
    {
        return array_intersect_key($haystack, array_flip($needle));
    }
}

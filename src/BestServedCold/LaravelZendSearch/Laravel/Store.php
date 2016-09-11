<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Laravel\Eloquent\EloquentTrait;
use BestServedCold\LaravelZendSearch\Lucene\Store as LuceneStore;
use Illuminate\Database\Eloquent\Model;

final class Store extends LuceneStore
{
    use EloquentTrait;

    public function insertModel(Model $model)
    {
        $this->deleteModel($model);
        $this->insert($model->id, $this->filterFields($model), $this->filterParameters($model), $this->uid);
    }

    private function filterFields($model)
    {
        return $this->filterKeysFromArray($model->attributesToArray(), $model::$searchFields);
    }

    private function filterParameters($model)
    {
        if (isset($model::$searchParameters)) {
            return $this->filterKeysFromArray($model->attributesToArray(), $model::$searchFields);
        }

        return [];
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

    public function deleteModel(Model $model)
    {
        return true;
    }

}

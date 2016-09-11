<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

trait SearchTrait
{
    private static function checks()
    {
        if (! isset(self::$searchFields) || ! is_array(self::$searchFields) || empty(self::$searchFields)) {
            throw new \Exception('$searchFields must exist, must be an array and cannot be empty');
        }

        if (! is_subclass_of(static::class, Model::class)) {
            throw new \Exception(
                'SearchTrait must only be used with Eloquent models, [' . get_called_class() . '] used'
            );
        }
    }

    public static function search()
    {
        self::checks();

        $search = App::make(Search::class);
        $search->model(new static);

        return $search;
    }

    public static function bootSearchTrait()
    {
        self::checks();

        $store = App::make(Store::class);

        self::saved(
            function (Model $model) use ($store) {
                self::insertCallback($model, $store);
            }
        );

        self::deleting(
            function (Model $model) use ($store) {
                self::deleteCallback($model, $store);
            }
        );
    }

    private static function insertCallback(Model $model, Store $store)
    {
        $store->model($model);
        $store->insertModel($model);
    }

    private static function deleteCallback(Model $model, Store $store)
    {
        $store->model($model);
        $store->deleteModel($model);
    }
}

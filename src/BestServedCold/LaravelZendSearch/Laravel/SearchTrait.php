<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

trait SearchTrait
{
    private static $searchFields = [];

    private static function checks()
    {
        if (! is_subclass_of(static::class, Model::class)) {
            throw new \Exception(
                'SearchTrait must only be used with Eloquent models, [' . get_called_class() . '] used'
            );
        }

        if (
            ! method_exists(get_class(), 'searchFields') ||
            ! (new \Reflectionmethod(get_class(), 'searchFields'))->isStatic()
        ) {
            throw new \Exception('Method [searchFields] must exist and be static');
        }

        static::searchFields();

        if (! isset(self::$searchFields) || ! is_array(self::$searchFields) || empty(self::$searchFields)) {
            throw new \Exception('$searchFields must exist, must be an array and cannot be empty');
        }
    }

    public static function getSearchFields()
    {
        return self::$searchFields;
    }

    private static function searchFields()
    {
        throw new \Exception("Method [searchFields] must exist and be static");
    }

    public function setSearchFields(array $fields)
    {
        self::$searchFields = $fields;
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

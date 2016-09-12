<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Class SearchTrait
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
trait SearchTrait
{
    private static $searchFields = [ ];
    private static $searchParameters = [ ];

    private static function setup()
    {
        if (!is_subclass_of(static::class, Model::class)) {
            throw new \Exception(
                'SearchTrait must only be used with Eloquent models, [' . get_called_class() . '] used'
            );
        }

        static::searchFields();
    }

    private static function searchFields()
    {
        throw new \Exception("Method [searchFields] must exist and be static");
    }

    /**
     * @param array $fields
     */
    public static function setSearchFields(array $fields)
    {
        self::$searchFields = $fields;
    }

    /**
     * @return array
     */
    public static function getSearchFields()
    {
        return self::$searchFields;
    }

    /**
     * @param array $parameters
     */
    public static function setSearchParameters(array $parameters)
    {
        self::$searchParameters = $parameters;
    }

    /**
     * @return array
     */
    public static function getSearchParameters()
    {
        return self::$searchParameters;
    }

    /**
     * @return mixed
     */
    public static function search()
    {
        self::setup();

        $search = App::make(Search::class);
        $search->model(new static);

        return $search;
    }

    public static function bootSearchTrait()
    {
        self::setup();

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

    /**
     * @param Model $model
     * @param Store $store
     */
    private static function insertCallback(Model $model, Store $store)
    {
        $store->model($model);
        $store->insertModel($model);
    }

    /**
     * @param Model $model
     * @param Store $store
     */
    private static function deleteCallback(Model $model, Store $store)
    {
        $store->model($model);
        $store->deleteModel($model);
    }
}

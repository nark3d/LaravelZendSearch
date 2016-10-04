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
    /**
     * @var array $searchFields
     */
    private static $searchFields = [ ];

    /**
     * Set up
     *
     * @throws \Exception
     * @return void
     */
    private static function setup()
    {
        // Ignoring PHP bug #53727 here, Eloquent Models implement several interfaces.
        if (!is_subclass_of(static::class, Model::class)) {
            throw new \Exception(
                'SearchTrait must only be used with Eloquent models, [' . get_called_class() . '] used.'
            );
        }

        static::searchFields();
    }

    /**
     * @throws \Exception
     */
    private static function searchFields()
    {
        throw new \Exception("Method [searchFields] must exist and be static.");
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
     * @return mixed
     */
    public static function search()
    {
        self::setup();

        $search = App::make(Search::class);
        $search->model(new static);

        return $search;
    }

    /**
     * @return void
     */
    public static function bootSearchTrait()
    {
        self::setup();
        $store = App::make(Store::class);
        self::saved(self::insertCallback($store));
        self::deleting(self::deleteCallback($store));
    }

    /**
     * @param Store $store
     * @return \Closure
     */
    private static function insertCallback(Store $store)
    {
        return function(Model $model) use ($store) {
            $store->model($model);
            $store->insertModel($model);
        };
    }

    /**
     * @param Store $store
     * @return \Closure
     */
    private static function deleteCallback(Store $store)
    {
        return function(Model $model) use ($store) {
            $store->model($model);
            $store->deleteModel($model);
        };
    }
}

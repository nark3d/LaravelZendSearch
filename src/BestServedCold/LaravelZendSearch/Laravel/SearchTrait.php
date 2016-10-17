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
    private static $searchFields = [];

    /**
     * @var array $boostFeilds
     */
    private static $boostFields = [];

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
                'SearchTrait must only be used with Eloquent models, ['.get_called_class().'] used.'
            );
        }

        static::searchFields();
    }

    /**
     * Search Fields
     *
     * This should never get called.  If it does get called, then it means that the Model which is using
     * "SearchTrait" has not declared a "searchFields" method and made it static.
     *
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
     * @param array $fields
     * @throws \Exception
     */
    public static function setBoostFields(array $fields)
    {
        if (!array_filter($fields, function($value) {
            return is_int($value) || is_float($value);
        })) {
            throw new \Exception('Boost field values must be integers or floats.');
        }
        self::$boostFields = $fields;
    }

    /**
     * @return array
     */
    public static function getBoostFields()
    {
        return self::$boostFields;
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

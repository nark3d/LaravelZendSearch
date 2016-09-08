<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Laravel\Model\Deleted;
use BestServedCold\LaravelZendSearch\Laravel\Model\Saved;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Config\Repository as Config;
use ZendSearch\Lucene\Search\Query\Boolean;
use BestServedCold\LaravelZendSearch\Lucene\Query;

trait SearchTrait
{
    /**
     * @var array
     */
    protected static $searchFields = [];

    public static function bootSearchTrait()
    {
        $eloquent = new Eloquent(new Index(new Config), new Query(new Boolean));

        self::saved(
            function (Model $model) use ($eloquent) {
                $eloquent->model($model);
                $saved = new Saved($model, $eloquent);
                $saved->update();
            }
        );

        self::deleting(
            function (Model $model) use ($eloquent) {
                $deleted = new Deleted($model, $eloquent);
            }
        );
    }

    /**
     * @param mixed $query
     */
    public static function Search($query)
    {
        if (empty(self::$searchFields)) {
            // @todo get better at excpetions!
            throw new \Exception;
        }

        echo "hello";
    }

    public function searchString($string)
    {

    }

    public function searchRaw($string)
    {

    }

    public function searchQuery(Search $search)
    {

    }

}

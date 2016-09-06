<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Laravel\Model\Deleted;
use BestServedCold\LaravelZendSearch\Laravel\Model\Saved;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Config\Repository;
use ZendSearch\Lucene\Search\Query\Boolean;
use BestServedCold\LaravelZendSearch\Lucene\Query;

trait SearchTrait
{
    public static function bootSearchTrait()
    {
        $eloquent = new Eloquent(new Index(new Repository), new Query(new Boolean));

        echo "<pre>";
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

}

<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model
{
    public static $count = 2;

    use SearchTrait;

    protected $primaryKey = 'different';

    public static function searchFields()
    {
        self::setSearchFields(['some', 'fields']);
    }

    public function whereIn($key, array $ids)
    {
        return $this;
    }

    public function get()
    {
        return ['some' => 'data'];
    }

    public function count()
    {
        return self::$count;
    }
}

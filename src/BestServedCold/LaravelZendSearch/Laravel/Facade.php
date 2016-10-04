<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

/**
 * Class Facade
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return             string
     * @codeCoverageIgnore
     */
    protected static function getFacadeAccessor()
    {
        return 'search';
    }
}

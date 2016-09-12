<?php

namespace BestServedCold\LaravelZendSearch\Search;

abstract class AbstractType
{
    protected $string;
    protected $field = false;
    protected $Options = [];

    /**
     * @param boolean $string
     * @param bool    $field
     * @param array   $options
     */
    public function __construct($string, $field = false, $options = [])
    {
        $this->string  = $string;
        $this->field   = $field;
        $this->options = $options;
    }
}

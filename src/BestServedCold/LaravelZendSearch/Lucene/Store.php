<?php

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\Lucene\Store\Insert;
use BestServedCold\LaravelZendSearch\Lucene\Store\Delete;

/**
 * Class Store
 *
 * @package BestServedCold\LaravelZendSearch\Lucene
 */
class Store
{
    /**
     * @var string
     */
    private $uid;

    /**
     * @var Delete
     */
    private $delete;

    /**
     * @var Insert
     */
    private $insert;

    /**
     * Store constructor.
     *
     * @param Delete $delete
     * @param Insert $insert
     */
    public function __construct(Delete $delete, Insert $insert)
    {
        $this->delete = $delete;
        $this->insert = $insert;
    }

    /**
     * Delete
     *
     * @param  integer $id
     * @param  bool    $uid
     * @return Delete
     */
    public function delete($id, $uid = false)
    {
        return $this->delete->delete($id, $uid ?: $this->uid);
    }

    /**
     * Insert
     *
     * @param  $id
     * @param  array $fields
     * @param  bool  $uid
     * @return mixed
     */
    public function insert($id, array $fields, $uid = false, $deleteFirst = true)
    {
        $deleteFirst ? $this->delete($id, $uid) : null;
        return $this->insert->insert($id, $fields, $uid ?: $this->uid);
    }

    /**
     * Uid
     *
     * @param  $uid
     * @return $this
     */
    public function uid($uid)
    {
        $this->uid = $uid;
        return $this;
    }
}

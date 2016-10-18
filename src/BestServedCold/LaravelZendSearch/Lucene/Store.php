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
     * @param  Index          $index
     * @param  integer        $id
     * @param  boolean|string $uid
     * @return Delete
     */
    public function delete(Index $index, $id, $uid = false)
    {
        return $this->delete->delete($index, $id, $uid);
    }

    /**
     * Insert
     *
     * @param  Index           $index
     * @param  int             $id
     * @param  array           $fields
     * @param  string|boolean  $uid
     * @param  boolean         $deleteFirst
     * @param  array           $boostFields
     * @return mixed
     */
    public function insert(
        Index $index,
        $id,
        array $fields,
        $uid = false,
        $deleteFirst = true,
        $boostFields = []
    ) {
        $deleteFirst ? $this->delete($index, $id, $uid) : null;
        return $this->insert->insert($index, $id, $fields, $uid, $boostFields);
    }
}

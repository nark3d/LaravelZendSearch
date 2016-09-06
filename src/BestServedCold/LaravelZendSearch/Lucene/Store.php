<?php 

namespace BestServedCold\LaravelZendSearch\Lucene;

use BestServedCold\LaravelZendSearch\Laravel\Lucene\Store\Insert;
use BestServedCold\LaravelZendSearch\Lucene\Store\Delete;

/**
 * Class Store
 *
 * @package BestServedCold\LaravelZendSearch\Lucene
 */
final class Store
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
     * @return $this
     */
    public function delete($id)
    {
        return $this->delete->delete($id, $this->uid);
    }

    /**
     * Insert
     *
     * @param $id
     * @param array $fields
     * @param array $parameters
     * @return mixed
     */
    public function insert($id, array $fields, array $parameters = [])
    {
        $this->delete($id);
        return $this->insert->insert($id, $fields, $parameters, $this->uid);
    }

    /**
     * Uid
     *
     * @param $uid
     * @return $this
     */
    public function uid($uid)
    {
        $this->uid = $uid;
        return $this;
    }
}

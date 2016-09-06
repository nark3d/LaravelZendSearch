<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Store;

use BestServedCold\LaravelZendSearch\Lucene\Index;
use BestServedCold\LaravelZendSearch\Lucene\Search;

/**
 * Class Delete
 *
 * @package BestServedCold\LaravelZendSearch\Lucene\Store
 */
final class Delete
{
    /**
     * @var Search
     */
    private $search;

    /**
     * @var
     */
    private $index;

    /**
     * Delete constructor.
     *
     * @param Search $search
     * @param Index  $index
     */
    public function __construct(Search $search, Index $index)
    {
        $this->search = $search;
        $this->index  = $index;
    }

    /**
     * Delete
     *
     * @param  $id
     * @param  bool $uid
     * @return $this
     */
    public function delete($id, $uid = false)
    {
        $this->search->where('xref_id', $id);

        if ($uid) {
            $this->search->where('uid', $uid);
        }

        foreach ($this->search->hits() as $hit) {
            $this->index->get()->delete($hit);
        }

        return $this;
    }
}

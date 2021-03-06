<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Store;

use BestServedCold\LaravelZendSearch\Lucene\Index;
use BestServedCold\LaravelZendSearch\Lucene\Search;

/**
 * Class Delete
 *
 * @package BestServedCold\LaravelZendSearch\Lucene\Store
 */
class Delete
{
    /**
     * @var Search
     */
    private $search;

    /**
     * Delete constructor.
     *
     * @param Search $search
     */
    public function __construct(Search $search)
    {
        $this->search = $search;
        $this->search->path(config('search.index.path'));
    }

    /**
     * Delete
     *
     * @param  Index          $index
     * @param  integer $id
     * @param  string|boolean $uid
     * @return $this
     */
    public function delete(Index $index, $id, $uid = false)
    {
        $this->search->match((int) $id, 'xref_id');

        if ($uid) {
            $this->search->where(base64_encode($uid), 'uid');
        }

        foreach ($this->search->hits(false) as $hit) {
            $index->get()->delete($hit);
        }

        $index->get()->commit();
        
        return $this;
    }
}

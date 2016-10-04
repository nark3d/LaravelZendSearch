<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Store;

use BestServedCold\LaravelZendSearch\Lucene\Index;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Document\Field;

/**
 * Class Insert
 *
 * @package BestServedCold\LaravelZendSearch\Lucene\Store
 */
class Insert
{
    /**
     * @var Index
     */
    private $index;

    /**
     * @var Document
     */
    private $document;

    /**
     * Insert constructor.
     *
     * @param Index    $index
     * @param Document $document
     */
    public function __construct(Index $index, Document $document)
    {
        $this->index    = $index;
        $this->document = $document;
    }

    /**
     * Insert
     *
     * @param  $id
     * @param  array          $fields     fields that are indexed
     * @param  boolean|string $uid        unique identifier, if required
     * @return mixed
     */
    public function insert($id, array $fields, $uid = false)
    {
        $this->document->addField($this->field('xref_id', $id));
        $this->document = $this->addUid($this->document, $uid);
        $this->document = $this->addFields($this->document, $fields);
        return $this->index->get()->addDocument($this->document);
    }

    /**
     * @param  string $keyword
     * @param  string $value
     * @param  string $type
     * @return Field
     */
    private function field($keyword, $value, $type = 'keyword')
    {
        return Field::$type($keyword, $value);
    }

    /**
     * @param  Document       $document
     * @param  boolean|string $uid
     * @return Document
     */
    private function addUid(Document $document, $uid = false)
    {
        if ($uid) {
            $document->addField($this->field('uid', strtoupper($uid)));
        }

        return $document;
    }

    /**
     * @param  Document $document
     * @param  array    $fields
     * @return Document
     */
    private function addFields(Document $document, array $fields)
    {
        foreach ($fields as $key => $field) {
            $document->addField($this->field($key, strtoupper($field)));
        }

        return $document;
    }
}

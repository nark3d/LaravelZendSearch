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
     * @var Document
     */
    private $document;

    /**
     * Insert constructor.
     *
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Insert
     *
     * @param  integer $id
     * @param  Index          $index
     * @param  array          $fields     fields that are indexed
     * @param  boolean|string $uid        unique identifier, if required
     * @return mixed
     */
    public function insert(Index $index, $id, array $fields, $uid = false)
    {
        $this->document->addField($this->field('xref_id', $id));
        $this->document = $this->addUid($this->document, $uid);
        $this->document = $this->addFields($this->document, $fields);
        return $index->get()->addDocument($this->document);
    }

    /**
     * @param  string $field
     * @param  string $value
     * @param  string $type
     * @return Field
     */
    private function field($field, $value, $type = 'keyword')
    {
        return Field::$type($field, strtolower(strip_tags($value)));
    }

    /**
     * @param  Document       $document
     * @param  boolean|string $uid
     * @return Document
     */
    private function addUid(Document $document, $uid = false)
    {
        if ($uid) {
            $document->addField($this->field('uid', $uid));
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
        foreach ($fields as $field => $text) {
            $document->addField($this->field($field, $text, 'text'));
        }

        return $document;
    }
}

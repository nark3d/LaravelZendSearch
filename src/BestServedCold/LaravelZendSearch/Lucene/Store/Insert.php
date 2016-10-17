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
     * @param  $id
     * @param  Index          $index
     * @param  array          $fields     fields that are indexed
     * @param  boolean|string $uid        unique identifier, if required
     * @return mixed
     */
    public function insert(Index $index, $id, array $fields, $uid = false)
    {
        $document = new Document;
        $document->addField($this->field('xref_id', $id));
        $document = $this->addUid($document, $uid);
        $document = $this->addFields($document, $fields);
        return $index->get()->addDocument($document);
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

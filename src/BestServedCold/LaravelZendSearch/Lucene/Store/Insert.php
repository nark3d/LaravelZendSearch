<?php

namespace BestServedCold\LaravelZendSearch\Lucene\Store;

use BestServedCold\LaravelZendSearch\Lucene\Index;
use BestServedCold\LaravelZendSearch\Lucene\Search;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Document\Field;

/**
 * Class Insert
 *
 * @package BestServedCold\LaravelZendSearch\Lucene\Store
 */
final class Insert
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
     * @param Search $search
     * @param Index $index
     * @param Document $document
     */
    public function __construct(Search $search, Index $index, Document $document)
    {
        $this->search   = $search;
        $this->index    = $index;
        $this->document = $document;
    }

    /**             
     * Insert
     *
     * @param  $id
     * @param  array       $fields     fields that are indexed
     * @param  array       $parameters fields that aren't indexed
     * @param  boolean|string $uid        unique identifier, if required
     * @return mixed
     */
    public function insert($id, array $fields, array $parameters = [ ], $uid = false)
    {
        $this->document->addField($this->field('xref_id', $id));
        $this->document->addField($this->field('_parameters', $this->flattenParameters($parameters), 'unIndexed'));
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
     * @param  Document $document
     * @param  bool     $uid
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

    /**
     * @param  $parameters
     * @return string
     * @todo   I'm not sure this is the right thing to do here, perhaps do some more investigation.
     */
    private function flattenParameters($parameters)
    {
        return base64_encode(json_encode($parameters));
    }
}

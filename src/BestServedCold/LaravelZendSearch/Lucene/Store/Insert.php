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
     * @var float
     */
    private $defaultBoost = 1.0;

    /**
     * @var null|Document
     */
    private static $lastInsert = null;

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
     * @param  array          $fields      fields that are indexed
     * @param  boolean|string $uid         unique identifier, if required
     * @param  array          $boostFields
     * @return mixed
     */
    public function insert(Index $index, $id, array $fields, $uid = false, array $boostFields = [])
    {
        $this->document->addField($this->field('xref_id', $id));
        $this->document = $this->addUid($this->document, $uid);
        $this->document = $this->addFields($this->document, $fields, $boostFields);
        self::$lastInsert = $this->document;
        return $index->get()->addDocument($this->document);
    }

    /**
     * @return null|Document
     */
    public static function getLastInsert()
    {
        return self::$lastInsert;
    }

    /**
     * @param  string|integer $field
     * @param  string         $value
     * @param  string         $type
     * @return Field
     */
    private function field($field, $value, $type = 'keyword', $boost = null)
    {
        $field = Field::$type($field, strtolower(strip_tags($value)));
        $field->boost = $boost ?: $this->defaultBoost;
        return $field;
    }

    /**
     * @param  Document       $document
     * @param  boolean|string $uid
     * @return Document
     */
    private function addUid(Document $document, $uid = false)
    {
        $uid && is_string($uid) ? $document->addField($this->field('uid', base64_encode($uid))) : null;
        return $document;
    }

    /**
     * @param  Document $document
     * @param  array    $fields
     * @param  array    $boostFields
     * @return Document
     */
    private function addFields(Document $document, array $fields, array $boostFields = [])
    {
        foreach ($fields as $field => $text) {
            $document->addField($this->field($field, $text, 'text', $this->boost($field, $boostFields)));
        }

        return $document;
    }

    /**
     * @param  $field
     * @param  array      $boostFields
     * @return mixed|null
     */
    private function boost($field, array $boostFields = [])
    {
        if (empty($boostFields)) {
            return null;
        }
        return array_key_exists($field, $boostFields) ? $boostFields[$field] : null;
    }
}

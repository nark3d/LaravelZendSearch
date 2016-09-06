<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\Lucene\Query;
use Illuminate\Database\Eloquent\Model;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Document\Field;
use BestServedCold\LaravelZendSearch\Lucene\Search as LuceneSearch;

final class Eloquent extends LuceneSearch
{
    private $table = false;
    private $key = 'id';

    public function model(Model $model)
    {
        $this->table = $model->getTable();
        $this->key = $model->getKey();
    }

    public function key($key)
    {
        $this->key = $key;
    }

    public function table($table)
    {
        $this->table = $table;
    }

    public function modelName($modelName)
    {
        $this->table = with(new $modelName)->getTable();
        $this->key = with(new $modelName)->getKey();
    }

    private function checkForTable()
    {
        if (! $this->table) {
            throw new \Exception('No table name set');
        }
    }

    public function find(Model $model)
    {
        $this->checkForTable();
        // @todo work this out obvs
        $this->path(storage_path('app') . '/lucene-search/index');
        $this->where('id', $model->id);
        return $this->hits();
    }

    private function getFields(Model $model)
    {
        // @todo make this method restrict the fields to the relevant ones with config?
        return $model->getAttributes();
    }

    private function createField($key, $string)
    {
        return Field::UnStored(trim($key), strip_tags($string));
    }

    public function add(Model $model)
    {
        $document = new Document;

        foreach ($this->getFields($model) as $key => $field) {
            $document->addField($this->createField($key, $field));
        }

        return $this->index->get()->addDocument($document);
    }

    public function delete(Model $model)
    {

    }
}

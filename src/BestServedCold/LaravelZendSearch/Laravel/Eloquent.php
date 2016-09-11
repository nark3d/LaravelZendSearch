<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Database\Eloquent\Model;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Document\Field;
use BestServedCold\LaravelZendSearch\Lucene\Search as LuceneSearch;
use BestServedCold\LaravelZendSearch\Laravel\Eloquent\EloquentTrait;

final class Eloquent extends LuceneSearch
{
    use EloquentTrait;

    public function find(Model $model)
    {
        $this->checkForTable();
        // @todo work this out obvs
//        $this->path(storage_path('app') . '/lucene-search/index');
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

        $this->index->get()->addDocument($document);
    }

    public function delete(Model $model)
    {

    }
}

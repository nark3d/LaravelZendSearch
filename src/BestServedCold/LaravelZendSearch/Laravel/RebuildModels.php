<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RebuildModels
 * @package BestServedCold\LaravelZendSearch\Laravel
 */
class RebuildModels
{
    private $progressBar;
    private $store;
    private $output;
    private $models = [ ];

    public function __construct(ProgressBar $progressBar, Store $store, OutputInterface $output)
    {
        $this->progressBar = $progressBar;
        $this->store = $store;
        $this->output = $output;
        $this->models = $this->getModels();
    }

    /**
     * @return array|bool
     */
    private function getModels()
    {
        $models = [];

        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, Model::class) && method_exists($class, 'searchFields')) {
                $models[] = $class;
            }
        }

        return empty($models) ? [] : $models;
    }

    public function setModels(array $models = [])
    {
        $this->models = $models;
    }

    /**
     * @param string $type
     * @param string $string
     */
    private function output($type, $string)
    {
        if (! $this->output instanceof NullOutput) {
            $this->output->$type($string);
        }
    }

    public function rebuild()
    {
        if (empty($this->models)) {
            $this->output('error', 'No models configured for search.');
        }

        $this->loopModels();

        $this->output('comment', PHP_EOL . 'Search engine rebuild complete.');
        return $this->output;
    }

    /**
     */
    private function loopModels()
    {
        foreach ($this->models as $modelName) {
            $this->rebuildModel(new $modelName);
        }
    }

    /**
     * @param Model $model
     */
    private function rebuildModel(Model $model)
    {
        $this->output('comment', 'Creating index for model [' . $model->getTable() . ']');

        if ($model->count() === 0) {
            $this->output(
                'comment',
                'No records for model [' . $model->getTable() . '].'
            );
            return;
        }

        $this->progressBar->start($model->count());
        $this->chunk($model);
        $this->progressBar->finish();
    }

    /**
     * @param Model $object
     */
    private function chunk(Model $object)
    {
        $object->chunk(1000, function($chunk) {
            foreach ($chunk as $record) {
                $this->store->insertModel($record, false);
                $this->progressBar->advance();
            }
        });
    }
}

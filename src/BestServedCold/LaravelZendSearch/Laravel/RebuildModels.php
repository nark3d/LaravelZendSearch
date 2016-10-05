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
    /**
     * @var ProgressBar $progressBar
     */
    private $progressBar;

    /**
     * @var Store $store
     */
    private $store;

    /**
     * @var OutputInterface $store
     */
    private $output;

    /**
     * @var array $models
     */
    private $models = [ ];

    /**
     * RebuildModels constructor.
     *
     * @param ProgressBar $progressBar
     * @param Store $store
     * @param OutputInterface $output
     */
    public function __construct(ProgressBar $progressBar, Store $store, OutputInterface $output)
    {
        $this->progressBar = $progressBar;
        $this->store = $store;
        $this->output = $output;
        $this->models = $this->getModels();
    }

    /**
     * @param array $models
     * @return array
     */
    private function getModels(array $models = [])
    {
        foreach (get_declared_classes() as $class) {
            // Ignoring PHP bug #53727 here, Eloquent Models implement several interfaces.
            if (is_subclass_of($class, Model::class) && method_exists($class, 'searchFields')) {
                $models[ ] = $class;
            }
        }

        return $models;
    }

    /**
     * @param array $models
     */
    public function setModels(array $models = [ ])
    {
        $this->models = $models;
    }

    /**
     * @param string $type
     * @param string $string
     */
    private function output($type, $string)
    {
        if (!$this->output instanceof NullOutput) {
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
     * Loop Models
     *
     * return @void
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

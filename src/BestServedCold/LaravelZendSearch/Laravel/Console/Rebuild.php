<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use BestServedCold\LaravelZendSearch\Laravel\Store;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Illuminate\Support\Facades\App;

/**
 * Class RebuildCommand
 * @package BestServedCold\LaravelZendSearch\Laravel\Console
 */
class Rebuild extends Command
{
    protected $name = 'search:rebuild';
    protected $description = 'Rebuild the search index';

    private $models = [ ];

    public function getModels()
    {
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, Model::class) && method_exists($class, 'searchFields')) {
                $this->models[ ] = $class;
            }
        }
    }

    public function handle()
    {
        $this->getModels();

        if (!$this->option('verbose')) {
            $this->output = new NullOutput;
        }

        $this->call('search:destroy', $this->getArguments());

        $store = App::make(Store::class);

        if (empty($this->models)) {
            $this->error('No models configured for search.');
        } else {
            foreach ($this->models as $model) {
                $object = App::make($model);

                $this->info('Creating index for model [' . $model . ']');

                $count = $object->count();

                if ($object->count() === 0) {
                    $this->comment('No records for model [' . $model . ']');
                    continue;
                }

                $progress = new ProgressBar($this->getOutput(), $count);
                $progress->start();

                $object->chunk(1000, function($chunk) use ($progress, $store) {
                        foreach ($chunk as $record) {
                            $store->insertModel($record, false);
                            $progress->advance();
                        }
                    }
                );

                $progress->finish();
            }

            $this->call('search:optimise', $this->getArguments());

            $this->info(PHP_EOL . 'Search engine rebuild complete.');
        }
    }
}

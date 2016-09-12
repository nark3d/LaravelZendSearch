<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use BestServedCold\LaravelZendSearch\Laravel\Index;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Class OptimiseCommand
 * @package BestServedCold\LaravelZendSearch\Laravel\Console
 */
class OptimiseCommand extends Command
{
    protected $name = 'search:optimise';
    protected $description = 'Optimise the search index storage';

    public function fire()
    {
        if (!$this->option('verbose')) {
            $this->output = new NullOutput;
        }

        $this->info('Optimising index.');
        $index = App::make(Index::class);
        $index->get()->optimize();
        $this->info('Optimising index finished.');
    }
}

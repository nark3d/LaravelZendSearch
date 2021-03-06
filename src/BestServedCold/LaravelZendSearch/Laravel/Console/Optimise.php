<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use BestServedCold\LaravelZendSearch\Laravel\Index;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

/**
 * Class OptimiseCommand
 * @package BestServedCold\LaravelZendSearch\Laravel\Console
 */
class Optimise extends Command
{
    /**
     * @var string $name
     */
    protected $name = 'search:optimise';

    /**
     * @var string $description
     */
    protected $description = 'Optimise the search index storage.';

    /**
     * Handle
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Optimising search index.');
        $index = App::make(Index::class);
        $index->open()->get()->optimize();
        $this->info('Optimising finished.');
    }
}

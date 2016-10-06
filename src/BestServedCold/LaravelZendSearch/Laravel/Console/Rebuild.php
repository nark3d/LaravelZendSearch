<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use BestServedCold\LaravelZendSearch\Laravel\RebuildModels;
use BestServedCold\LaravelZendSearch\Laravel\Store;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RebuildCommand
 * @package BestServedCold\LaravelZendSearch\Laravel\Console
 */
class Rebuild extends Command
{
    /**
     * @var string $name
     */
    protected $name = 'search:rebuild';

    /**
     * @var string $description
     */
    protected $description = 'Rebuild the search index';

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Handle
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->option('verbose')) {
            $this->output = new NullOutput;
        }

        $rebuild = new RebuildModels(
            new ProgressBar($this->getOutput()),
            App::make(Store::class),
            $this->output
        );

        $this->call('search:destroy', $this->getArguments());
        $rebuild->rebuild();

        $this->call('search:optimise', $this->getArguments());
        $this->info(PHP_EOL.'Search engine rebuild complete.');
    }
}

<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Symfony\Component\Console\Output\NullOutput;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

/**
 * Class ClearCommand
 * @package BestServedCold\LaravelZendSearch\Laravel\Console
 */
class DestroyCommand extends Command
{
    protected $name = 'search:destroy';
    protected $description = 'Destroys the search index storage.';

    public function fire()
    {
        if (!$this->option('verbose')) {
            $this->output = new NullOutput;
        }

        $config = App::make(Repository::class);

        $this->info('Clearing search index');

        if (File::isDirectory($indexPath = $config->get('search.index.path'))) {
            File::deleteDirectory($indexPath);
            $this->info('Search index is cleared.');
        } else {
            $this->comment('There was nothing to clear.');
        }
    }
}

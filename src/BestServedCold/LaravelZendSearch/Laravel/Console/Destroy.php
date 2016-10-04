<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

/**
 * Class ClearCommand
 * @package BestServedCold\LaravelZendSearch\Laravel\Console
 */
class Destroy extends Command
{
    /**
     * @var string $name
     */
    protected $name = 'search:destroy';

    /**
     * @var string $description
     */
    protected $description = 'Destroys the search index.';

    /**
     * Handle
     *
     * @return void
     */
    public function handle()
    {
        $config = App::make(Repository::class);

        $indexPath = $config->get('search.index.path');

        $this->info('Destroying the search index.');

        if (File::isDirectory($indexPath)) {
            File::deleteDirectory($indexPath);
            $this->info('Search index is destroyed.');
        } else {
            $this->comment('There was nothing to destroy?  Try a rebuild.');
        }
    }
}

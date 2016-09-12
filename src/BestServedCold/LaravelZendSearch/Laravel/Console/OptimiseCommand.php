<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use Illuminate\Console\Command;
use Config;
use Symfony\Component\Console\Output\NullOutput;

class OptimiseCommand extends Command
{
    protected $name = 'search:optimise';
    protected $description = 'Optimise the search index storage';

    public function fire()
    {

    }
}

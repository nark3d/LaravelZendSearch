<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Console;

use BestServedCold\LaravelZendSearch\Laravel\DummyModel;
use BestServedCold\LaravelZendSearch\Laravel\Index;
use BestServedCold\LaravelZendSearch\Laravel\Filter;
use BestServedCold\LaravelZendSearch\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem\Filesystem;
use ZendSearch\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive;

/**
 * Class RebuildTest
 * @package BestServedCold\LaravelZendSearch\Laravel\Console
 */
class RebuildTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->loadMigrationsFrom(
            ['--database' => 'testbench', '--realpath' => realpath('tests/migrations')]
        );
    }

    public function testHandle()
    {
        $index = new Index(new Filter(new CaseInsensitive), $this->app->config, new Filesystem);
        $index->open($this->indexPath);
        Artisan::call('search:rebuild', ['--verbose' => true]);
        $this->assertContains('Search engine rebuild complete', Artisan::output());
    }

    public function testModelHasNoRecords()
    {
        $index = new Index(new Filter(new CaseInsensitive), $this->app->config, new Filesystem);
        $index->open($this->indexPath);

        DB::table('dummy_models')->truncate();
        DummyModel::$count = 0;

        Artisan::call('search:rebuild', ['--verbose' => true]);
        $this->assertContains(
            'No records for model [dummy_models]',
            Artisan::output()
        );
    }

    public function testNoOutput()
    {
        $index = new Index(new Filter(new CaseInsensitive), $this->app->config, new Filesystem);
        $index->open($this->indexPath);
        Artisan::call('search:rebuild');
        $this->assertEquals('', Artisan::output());
    }
}

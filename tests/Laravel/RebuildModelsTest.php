<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\TestCase;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Console\OutputStyle;

class RebuildModelsTest extends TestCase
{
    private $rebuildModels;
    private $error;
    private $chunked;

    public function setUp()
    {
        parent::setUp();
        $store = $this->getMockBuilder(Store::class)->disableOriginalConstructor()->getMock();
        $store->method('insertModel')->willReturnCallback(function() {
            $this->chunked = true;
        });
        $output = $this->getMockBuilder(OutputStyle::class)->disableOriginalConstructor()->getMock();
        $output->method('error')->willReturnCallback(function($message) {
            $this->error = $message;
        });
        $output->method('getFormatter')->willReturn(new OutputFormatter);

        $progressBar = new ProgressBar($output);
        $this->rebuildModels = new RebuildModels($progressBar, $store, $output);

        $this->loadMigrationsFrom(
            ['--database' => 'testbench', '--realpath' => realpath('tests/migrations')]
        );
    }

    public function testChunk()
    {
        DummyModel::insert(['data' => 'hello adam']);
        DummyModel::insert(['data' => 'some other data']);
        DummyModel::$count = '2';

        $this->rebuildModels->rebuild();
        $this->assertTrue($this->chunked);

    }

    public function testRebuildWithNoModels()
    {
        $this->rebuildModels->setModels();
        $this->rebuildModels->rebuild();
        $this->assertSame('No models configured for search.', $this->error);
    }
}

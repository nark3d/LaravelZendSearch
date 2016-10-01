<?php

namespace BestServedCold\LaravelZendSearch\Laravel;

use BestServedCold\LaravelZendSearch\TestCase;

class ServiceProviderTest extends TestCase
{
    private $serviceProvider;

    public function setUp()
    {
        parent::setUp();

        $this->serviceProvider = new ServiceProvider($this->app);
    }

    public function testBoot()
    {
        $this->assertNull($this->serviceProvider->boot());
    }

    public function testProvides()
    {
        $this->assertEquals(
            [ 'search', 'command.search.rebuild', 'command.search.optimise', 'command.search.destroy' ],
            $this->serviceProvider->provides()
        );
    }
}

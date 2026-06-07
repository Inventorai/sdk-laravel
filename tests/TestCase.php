<?php

namespace Inventorai\Laravel\Tests;

use Inventorai\Laravel\InventoraiServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            InventoraiServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Inventorai' => \Inventorai\Laravel\Facades\Inventorai::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('inventorai.token', 'test-token');
        $app['config']->set('inventorai.base_url', 'https://api.inventorai.co.uk/v1/team');
    }
}

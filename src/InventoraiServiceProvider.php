<?php

namespace Inventorai\Laravel;

use Illuminate\Support\ServiceProvider;
use Inventorai\SDK\InventoraiClient;

class InventoraiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/inventorai.php', 'inventorai');

        $this->app->singleton(InventoraiClient::class, function ($app) {
            $config = $app['config']['inventorai'];

            if (empty($config['token'])) {
                throw new \InvalidArgumentException(
                    'Inventorai API token not configured. Set INVENTORAI_API_TOKEN in your .env file.'
                );
            }

            return new InventoraiClient($config['token'], $config['base_url']);
        });

        $this->app->alias(InventoraiClient::class, 'inventorai');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/inventorai.php' => config_path('inventorai.php'),
            ], 'inventorai-config');

            $this->commands([
                Console\TestConnectionCommand::class,
            ]);
        }
    }
}

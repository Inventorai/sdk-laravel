<?php

namespace Inventorai\Laravel\Console;

use Illuminate\Console\Command;
use Inventorai\SDK\InventoraiClient;
use Inventorai\SDK\Exceptions\AuthenticationException;
use Inventorai\SDK\Exceptions\ApiException;

class TestConnectionCommand extends Command
{
    protected $signature = 'inventorai:test';
    protected $description = 'Test the connection to the Inventorai API';

    public function handle(InventoraiClient $client): int
    {
        $this->info('Testing Inventorai API connection...');
        $this->newLine();

        $baseUrl = config('inventorai.base_url');
        $this->line("  Base URL: {$baseUrl}");
        $this->line("  Token: " . str_repeat('*', 20) . substr(config('inventorai.token'), -4));
        $this->newLine();

        try {
            $team = $client->team()->current();

            $this->info('Connection successful!');
            $this->line("  Team: {$team['data']['name']}");

            return self::SUCCESS;
        } catch (AuthenticationException $e) {
            $this->error('Authentication failed: ' . $e->getMessage());
            $this->newLine();
            $this->line('  Check your INVENTORAI_API_TOKEN in .env');

            return self::FAILURE;
        } catch (ApiException $e) {
            $this->error('API error: ' . $e->getMessage());

            return self::FAILURE;
        } catch (\Exception $e) {
            $this->error('Connection failed: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}

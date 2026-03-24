<?php

use Inventorai\SDK\InventoraiClient;

it('registers the inventorai client in the container', function () {
    expect(app()->bound(InventoraiClient::class))->toBeTrue();
});

it('resolves the client as a singleton', function () {
    $client1 = app(InventoraiClient::class);
    $client2 = app(InventoraiClient::class);

    expect($client1)->toBe($client2);
});

it('resolves via the alias', function () {
    $client = app('inventorai');

    expect($client)->toBeInstanceOf(InventoraiClient::class);
});

it('throws when token is not configured', function () {
    config(['inventorai.token' => null]);

    // Clear the singleton so it re-resolves
    app()->forgetInstance(InventoraiClient::class);

    app(InventoraiClient::class);
})->throws(\InvalidArgumentException::class, 'Inventorai API token not configured');

<?php

use Inventorai\Laravel\Facades\Inventorai;
use Inventorai\SDK\InventoraiClient;

it('resolves the facade to the client', function () {
    expect(Inventorai::getFacadeRoot())->toBeInstanceOf(InventoraiClient::class);
});

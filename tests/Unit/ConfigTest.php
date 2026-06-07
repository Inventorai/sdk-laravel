<?php

it('has the correct default base url', function () {
    expect(config('inventorai.base_url'))->toBe('https://api.inventorai.co.uk/v1/team');
});

it('uses the configured token', function () {
    expect(config('inventorai.token'))->toBe('test-token');
});

it('merges config correctly', function () {
    expect(config('inventorai'))->toBeArray()
        ->toHaveKeys(['token', 'base_url']);
});

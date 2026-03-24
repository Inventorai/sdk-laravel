<?php

it('registers the artisan command', function () {
    $this->artisan('list')
        ->expectsOutputToContain('inventorai:test');
});

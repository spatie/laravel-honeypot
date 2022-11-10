<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Spatie\TestTime\TestTime;

use function Spatie\Snapshots\assertMatchesSnapshot;

uses(InteractsWithViews::class);

test('the Blade component can render the honeypot field', function () {
    TestTime::freeze('Y-m-d H:i:s', '2020-01-01 00:00:00');
    config()->set('honeypot.randomize_name_field_name', false);

    $renderedBladeComponent = $this->blade('<x-honeypot />');

    assertMatchesSnapshot($renderedBladeComponent);
});

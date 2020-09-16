<?php

namespace Spatie\Honeypot\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Spatie\Snapshots\MatchesSnapshots;
use Spatie\TestTime\TestTime;

class HoneypotBladeComponentTest extends TestCase
{
    use InteractsWithViews;
    use MatchesSnapshots;

    /** @test */
    public function the_blade_component_can_render_the_honeypot_field()
    {
        TestTime::freeze('Y-m-d H:i:s', '2020-01-01 00:00:00');
        config()->set('honeypot.randomize_name_field_name', false);

        $renderedBladeComponent = $this->blade('<x-honeypot />');

        $this->assertMatchesSnapshot($renderedBladeComponent);
    }
}

<?php

namespace Spatie\Honeypot\Tests;

use Spatie\Snapshots\MatchesSnapshots;

class HoneypotBladeDirectiveTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function the_honeypot_blade_directive_renders_correctly()
    {
        config()->set('honeypot.randomize_name_field_name', false);

        $renderedView = view('honeypot')->render();

        $this->assertMatchesSnapshot($renderedView);
    }
}

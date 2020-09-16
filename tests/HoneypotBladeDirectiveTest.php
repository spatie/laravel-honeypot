<?php

namespace Spatie\Honeypot\Tests;

use Carbon\CarbonImmutable;
use Illuminate\Support\DateFactory;
use Spatie\Snapshots\MatchesSnapshots;
use Spatie\TestTime\TestTime;

class HoneypotBladeDirectiveTest extends TestCase
{
    use MatchesSnapshots;

    public function setUp(): void
    {
        parent::setUp();

        config()->set('honeypot.randomize_name_field_name', false);
    }

    /** @test */
    public function the_honeypot_blade_directive_renders_correctly()
    {
        TestTime::freeze('Y-m-d H:i:s', '2019-01-01 00:00:00');

        $renderedView = view('honeypot')->render();

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function the_honeypot_blade_directive_renders_correctly_when_using_CarbonImmutable()
    {
        DateFactory::use(CarbonImmutable::class);
        TestTime::freeze('Y-m-d H:i:s', '2019-01-01 00:00:00');

        $renderedView = view('honeypot')->render();

        $this->assertMatchesSnapshot($renderedView);

        DateFactory::use(DateFactory::DEFAULT_CLASS_NAME);
    }
}

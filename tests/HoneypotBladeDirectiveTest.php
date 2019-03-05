<?php

namespace Spatie\Honeypot\Tests;

use Carbon\CarbonImmutable;
use Illuminate\Support\DateFactory;
use Spatie\Snapshots\MatchesSnapshots;

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
        $renderedView = view('honeypot')->render();

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function the_honeypot_blade_directive_renders_correctly_when_using_CarbonImmutable()
    {
        if (! class_exists(CarbonImmutable::class)) {
            $this->markTestSkipped('Test for Carbon 2 only');
        }

        DateFactory::use(CarbonImmutable::class);

        $renderedView = view('honeypot')->render();

        $this->assertMatchesSnapshot($renderedView);

        DateFactory::use(DateFactory::DEFAULT_CLASS_NAME);
    }
}

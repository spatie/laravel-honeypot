<?php

namespace Spatie\Honeypot\Tests;

use Exception;
use Livewire\Livewire;
use Spatie\Honeypot\Tests\TestComponents\LivewireHoneypotComponent;
use Spatie\Honeypot\Tests\TestComponents\LivewireHoneypotConfiguredComponent;
use Spatie\TestTime\TestTime;

class HoneypotLivewireComponentTest extends TestCase
{
    /** @test */
    public function it_throws_exception_because_the_component_is_not_well_configured()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Livewire component requires a `HoneypotData` property.");

        Livewire::test(LivewireHoneypotComponent::class)
            ->call('submit')
            ->assertOk();
    }

    /** @test */
    public function works_if_honeypot_is_disabled()
    {
        config()->set('honeypot.enabled', false);

        Livewire::test(LivewireHoneypotConfiguredComponent::class)
            ->call('submit')
            ->assertOk();
    }

    /** @test */
    public function permission_denied_if_request_is_done_too_early()
    {
        Livewire::test(LivewireHoneypotConfiguredComponent::class)
            ->call('submit')
            ->assertStatus(403);
    }

    /** @test */
    public function permission_denied_if_request_is_spam()
    {
        config()->set('honeypot.randomize_name_field_name', false);
        config()->set('honeypot.name_field_name', 'firstname');

        Livewire::test(LivewireHoneypotConfiguredComponent::class)
            ->set('extraFields.firstname', 'I am a spammer')
            ->call('submit')
            ->assertStatus(403);
    }

    /** @test */
    public function it_works()
    {
        TestTime::freeze('Y-m-d H:i:s', '2019-01-01 00:00:00');

        $component = Livewire::test(LivewireHoneypotConfiguredComponent::class);

        TestTime::freeze('Y-m-d H:i:s', '2019-01-01 00:01:00');

        $component
            ->call('submit')
            ->assertOk()
            ->assertSet('success', true);
    }
}

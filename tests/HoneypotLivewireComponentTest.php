<?php

use function Pest\Livewire\livewire;
use Spatie\Honeypot\Tests\TestComponents\LivewireHoneypotComponent;
use Spatie\Honeypot\Tests\TestComponents\LivewireHoneypotConfiguredComponent;

use Spatie\TestTime\TestTime;

it('throws exception because the component is not well configured')
    ->throws(Exception::class, "Livewire component requires a `HoneypotData` property.")
    ->livewire(LivewireHoneypotComponent::class)
    ->call('submit')
    ->assertOk();

test('works if honeypot is disabled')
    ->tap(fn () => config()->set('honeypot.enabled', false))
    ->livewire(LivewireHoneypotConfiguredComponent::class)
    ->call('submit')
    ->assertOk();

test('permission denied if request is done too early')
    ->livewire(LivewireHoneypotConfiguredComponent::class)
    ->call('submit')
    ->assertStatus(403);

test('permission denied if request is spam')
    ->tap(function () {
        config()->set('honeypot.randomize_name_field_name', false);
        config()->set('honeypot.name_field_name', 'firstname');
    })
    ->livewire(LivewireHoneypotConfiguredComponent::class)
    ->set('extraFields.firstname', 'I am a spammer')
    ->call('submit')
    ->assertStatus(403);
    ;

test('it works', function () {
    TestTime::freeze('Y-m-d H:i:s', '2019-01-01 00:00:00');

    $component = livewire(LivewireHoneypotConfiguredComponent::class);

    TestTime::freeze('Y-m-d H:i:s', '2019-01-01 00:01:00');

    $component
        ->call('submit')
        ->assertOk()
        ->assertSet('success', true);
});

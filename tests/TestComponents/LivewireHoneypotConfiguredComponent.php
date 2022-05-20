<?php

namespace Spatie\Honeypot\Tests\TestComponents;

use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;

class LivewireHoneypotConfiguredComponent extends LivewireHoneypotComponent
{
    public HoneypotData $extraFields;

    public function mount()
    {
        $this->extraFields = new HoneypotData();
    }

    public function render()
    {
        return <<<'blade'
            <form wire:submit.prevent="submit" method="POST">
                @honeypot
                <input name="myField" type="text">
            </form>
        blade;
    }
}

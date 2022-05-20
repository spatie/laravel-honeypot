<?php

namespace Spatie\Honeypot\Tests\TestComponents;

use Livewire\Component;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;

class LivewireHoneypotComponent extends Component
{
    use UsesSpamProtection;

    public bool $success = false;

    public function submit()
    {
        $this->success = false;

        $this->protectAgainstSpam();

        $this->success = true;
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

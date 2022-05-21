<?php

namespace Spatie\Honeypot\View;

use Illuminate\View\Component;
use Spatie\Honeypot\Honeypot;

class HoneypotComponent extends Component
{
    public function __construct(
        protected Honeypot $setup,
        public ?string $livewireModel = null,
    ) {
    }

    public function render()
    {
        return view('honeypot::honeypotFormFields', $this->setup->toArray());
    }
}

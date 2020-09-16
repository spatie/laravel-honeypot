<?php

namespace Spatie\Honeypot\View;

use Illuminate\View\Component;
use Spatie\Honeypot\HoneypotSetup;

class HoneypotComponent extends Component
{
    protected HoneypotSetup $setup;

    public function __construct(HoneypotSetup $setup)
    {
        $this->setup = $setup;
    }

    public function render()
    {
        return view('honeypot::honeypotFormFields', [
            'enabled' => $this->setup->enabled(),
            'nameFieldName' => $this->setup->nameFieldName(),
            'validFromFieldName' => $this->setup->validFromFieldName(),
            'encryptedValidFrom' => $this->setup->encryptedValidFrom(),
        ]);
    }
}
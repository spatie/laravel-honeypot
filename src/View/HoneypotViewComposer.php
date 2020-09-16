<?php

namespace Spatie\Honeypot\View;

use Illuminate\View\View;
use Spatie\Honeypot\HoneypotSetup;

class HoneypotViewComposer
{
    public function compose(View $view)
    {
        $setup = app(HoneypotSetup::class);

        $view
            ->with('enabled', $setup->enabled())
            ->with('nameFieldName', $setup->nameFieldName())
            ->with('validFromFieldName', $setup->validFromFieldName())
            ->with('encryptedValidFrom', $setup->encryptedValidFrom());
    }
}

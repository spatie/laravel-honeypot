<?php

namespace Spatie\Honeypot;

use Illuminate\View\View;

class HoneypotViewComposer
{
    public function compose(View $view)
    {
        $fields = HoneypotSetup::get();

        $view->with('enabled', $fields['enabled'])
            ->with('nameFieldName', $fields['nameFieldName'])
            ->with('validFromFieldName', $fields['validFromFieldName'])
            ->with('encryptedValidFrom', $fields['encryptedValidFrom']);
    }
}

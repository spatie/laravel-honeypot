<?php

namespace Spatie\Honeypot;

use Illuminate\View\View;

class HoneypotViewComposer
{
    public function compose(View $view)
    {
        $honeypotConfig = config('honeypot');

        $enabled = $honeypotConfig['enabled'];
        $nameFieldName = $honeypotConfig['name_field_name'];
        $validFromFieldName = $honeypotConfig['valid_from_field_name'];

        $validFrom = now()->addSeconds($honeypotConfig['amount_of_seconds']);

        $encrypedValidFrom = EncrypedTime::create($validFrom);

        $view->with(compact(
            'enabled',
            'nameFieldName',
            'validFromFieldName',
            'encrypedValidFrom'
        ));
    }
}

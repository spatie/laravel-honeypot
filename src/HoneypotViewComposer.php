<?php

namespace Spatie\Honeypot;

use Illuminate\Support\Str;
use Illuminate\View\View;

class HoneypotViewComposer
{
    public function compose(View $view)
    {
        $honeypotConfig = config('honeypot');

        $nameFieldName = $honeypotConfig['name_field_name'];

        $randomNameFieldName = $honeypotConfig['randomize_name_field_name'];
        $enabled = $honeypotConfig['enabled'];
        $validFromFieldName = $honeypotConfig['valid_from_field_name'];

        $validFrom = now()->addSeconds($honeypotConfig['amount_of_seconds']);

        $encryptedValidFrom = EncryptedTime::create($validFrom);

        if ($randomNameFieldName) {
            $nameFieldName = sprintf('%s_%s', $nameFieldName, Str::random());
        }

        $view->with(compact(
            'enabled',
            'nameFieldName',
            'validFromFieldName',
            'encryptedValidFrom'
        ));
    }
}

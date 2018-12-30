<?php

namespace Spatie\Honeypot;

use Illuminate\View\View;
use Illuminate\Support\Str;

class HoneypotViewComposer
{
    public function compose(View $view)
    {
        $honeypotConfig = config('honeypot');

        $nameFieldName = $honeypotConfig['name_field_name'];

        if ($honeypotConfig['random_name_field_name']) {
            $randomString = Str::random();
            session(['name_field_name' => $randomString]);
            $nameFieldName = $randomString;
        }

        $enabled = $honeypotConfig['enabled'];
        $validFromFieldName = $honeypotConfig['valid_from_field_name'];

        $validFrom = now()->addSeconds($honeypotConfig['amount_of_seconds']);

        $encryptedValidFrom = EncryptedTime::create($validFrom);

        $view->with(compact(
            'enabled',
            'nameFieldName',
            'validFromFieldName',
            'encryptedValidFrom'
        ));
    }

    protected function generateRandomString(): String
    {
        return Str::random();
    }
}

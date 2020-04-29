<?php

namespace Spatie\Honeypot;

use Illuminate\Support\Str;

class HoneypotSetup
{
    /**
     * Get an array with values needed for composing the view
     * It allows to use it outside a blade component so it
     * can be passed to a front-end framework like vue.
     *
     * @return array
     */
    public static function get()
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

        return [
            'enabled' => $enabled,
            'nameFieldName' => $nameFieldName,
            'validFromFieldName' => $validFromFieldName,
            'encryptedValidFrom' => strval($encryptedValidFrom),
        ];
    }
}

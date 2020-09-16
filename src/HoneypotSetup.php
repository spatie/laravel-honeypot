<?php

namespace Spatie\Honeypot;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class HoneypotSetup
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function nameFieldName(): string
    {
        $nameFieldName = $this->config['name_field_name'];

        if ($this->randomizeNameFieldName()) {
            return sprintf('%s_%s', $nameFieldName, Str::random());
        }

        return $nameFieldName;
    }

    public function randomizeNameFieldName(): bool
    {
        return $this->config['randomize_name_field_name'];
    }

    public function enabled(): bool
    {
        return $this->config['enabled'];
    }

    public function validFromFieldName(): string
    {
        return $this->config['valid_from_field_name'];
    }

    public function validFrom(): CarbonInterface
    {
        return now()->addSeconds($this->config['amount_of_seconds']);
    }

    public function encryptedValidFrom(): string
    {
        return strval(EncryptedTime::create($this->validFrom()));
    }

    public function toArray()
    {
        return [
            'enabled' => $this->enabled(),
            'nameFieldName' => $this->nameFieldName(),
            'validFromFieldName' => $this->validFromFieldName(),
            'encryptedValidFrom' => $this->encryptedValidFrom(),
        ];
    }

    /*
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
    */
}

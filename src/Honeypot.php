<?php

namespace Spatie\Honeypot;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class Honeypot implements Arrayable
{
    public function __construct(
        protected array $config
    ) {
    }

    public function unrandomizedNameFieldName(): string
    {
        return $this->config['name_field_name'];
    }

    public function nameFieldName(): string
    {
        $nameFieldName = $this->unrandomizedNameFieldName();

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
        return EncryptedTime::create($this->validFrom());
    }

    #[ArrayShape([
        'enabled' => "bool",
        'nameFieldName' => "string",
        'unrandomizedNameFieldName' => "string",
        'validFromFieldName' => "string",
        'encryptedValidFrom' => "string",
    ])]
    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled(),
            'nameFieldName' => $this->nameFieldName(),
            'unrandomizedNameFieldName' => $this->unrandomizedNameFieldName(),
            'validFromFieldName' => $this->validFromFieldName(),
            'encryptedValidFrom' => $this->encryptedValidFrom(),
        ];
    }
}

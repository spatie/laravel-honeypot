<?php

namespace Spatie\Honeypot\Http\Livewire\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Livewire\Wireable;
use Spatie\Honeypot\Honeypot;

class HoneypotData implements Wireable, Arrayable
{
    public function __construct(public array $data = [])
    {
        if (filled($data)) {
            return;
        }

        $setup = app(Honeypot::class);

        $nameFieldName = $setup->unrandomizedNameFieldName();
        $validFromFieldName = $setup->validFromFieldName();

        $this->data = [
            $nameFieldName => '',
            $validFromFieldName => $setup->encryptedValidFrom(),
        ];
    }

    public function toLivewire(): array
    {
        return $this->data;
    }

    public static function fromLivewire($value): self
    {
        return new static($value);
    }

    public function __get(string $name): string
    {
        return Arr::get($this->data, $name);
    }

    public function __isset(string $name): bool
    {
        return Arr::has($this->data, $name);
    }

    public function __set(string $name, $value): void
    {
        Arr::set($this->data, $name, $value);
    }

    public function toArray(): array
    {
        $setup = app(Honeypot::class);

        $nameFieldName = $setup->unrandomizedNameFieldName();
        $validFromFieldName = $setup->validFromFieldName();

        return [
            $setup->nameFieldName() => Arr::get($this->data, $nameFieldName),
            $setup->validFromFieldName() => Arr::get($this->data, $validFromFieldName),
        ];
    }
}

<?php

namespace Spatie\Honeypot;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Date;
use Spatie\Honeypot\Exceptions\InvalidTimestamp;

class EncryptedTime
{
    protected CarbonInterface $carbon;

    protected string $encryptedTime;

    public static function create(DateTimeInterface $dateTime)
    {
        $encryptedTime = app('encrypter')->encrypt($dateTime->getTimestamp());

        return new static($encryptedTime);
    }

    public function __construct(string $encryptedTime)
    {
        $this->encryptedTime = $encryptedTime;

        try {
            $timestamp = app('encrypter')->decrypt($encryptedTime);
        } catch (DecryptException $e) {
            throw InvalidTimestamp::make($encryptedTime);
        }

        if (! $this->isValidTimeStamp($timestamp)) {
            throw InvalidTimestamp::make($timestamp);
        }

        $this->carbon = Date::createFromTimestamp($timestamp);
    }

    public function isFuture(): bool
    {
        return $this->carbon->isFuture();
    }

    protected function isValidTimeStamp(string $timestamp): bool
    {
        return (string) (int) $timestamp === $timestamp
            && $timestamp > 0
            && $timestamp < PHP_INT_MAX;
    }

    public function __toString()
    {
        return $this->encryptedTime;
    }
}

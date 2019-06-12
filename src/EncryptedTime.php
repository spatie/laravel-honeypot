<?php

namespace Spatie\Honeypot;

use DateTimeInterface;
use Illuminate\Support\Facades\Date;

class EncryptedTime
{
    protected $carbon;

    /** @var string */
    protected $encryptedTime;

    public static function create(DateTimeInterface $dateTime)
    {
        $encryptedTime = app('encrypter')->encrypt($dateTime->getTimestamp());

        return new static($encryptedTime);
    }

    public function __construct(string $encryptedTime)
    {
        $this->encryptedTime = $encryptedTime;

        $timestamp = app('encrypter')->decrypt($encryptedTime);

        if (! $this->isValidTimeStamp($timestamp)) {
            throw new \Exception(sprintf('Timestamp %s is invalid', $timestamp));
        }

        $this->carbon = Date::createFromTimestamp($timestamp);
    }

    public function isFuture(): bool
    {
        return $this->carbon->isFuture();
    }

    public function __toString()
    {
        return $this->encryptedTime;
    }

    private function isValidTimeStamp(string $timestamp): bool
    {
        if ((string) (int) $timestamp !== $timestamp) {
            return false;
        }

        if ($timestamp <= 0) {
            return false;
        }

        if ($timestamp >= PHP_INT_MAX) {
            return false;
        }

        return true;
    }
}

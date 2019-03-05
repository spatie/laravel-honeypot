<?php

namespace Spatie\Honeypot;

use Carbon\Carbon;
use DateTimeInterface;

class EncryptedTime
{
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

        $this->carbon = Carbon::createFromTimestamp($timestamp);
    }

    public function isFuture(): bool
    {
        return $this->carbon->isFuture();
    }

    public function __toString()
    {
        return $this->encryptedTime;
    }
}

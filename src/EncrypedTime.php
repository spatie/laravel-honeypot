<?php

namespace Spatie\Honeypot;

use Carbon\Carbon;

class EncrypedTime
{
    /** @var string */
    protected $encrypedTime;

    public static function create(Carbon $carbon)
    {
        $encryptedTime = app('encrypter')->encrypt($carbon->timestamp);

        return new static($encryptedTime);
    }

    public function __construct(string $encrypedTime)
    {
        $this->encrypedTime = $encrypedTime;

        $timestamp = app('encrypter')->decrypt($encrypedTime);

        $this->carbon = Carbon::createFromTimestamp($timestamp);
    }

    public function isFuture(): bool
    {
        return $this->carbon->isFuture();
    }

    public function __toString()
    {
        return $this->encrypedTime;
    }
}

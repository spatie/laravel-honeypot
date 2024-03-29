<?php

namespace Spatie\Honeypot\Tests\TestClasses;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Str;

class FakeEncrypter implements Encrypter
{
    public function encrypt($value, $serialize = true)
    {
        return $value.'-encrypted';
    }

    public function decrypt($payload, $unserialize = true)
    {
        return Str::before($payload, '-encrypted');
    }

    public function getKey()
    {
        return 1;
    }

    public function getAllKeys()
    {
        return [];
    }

    public function getPreviousKeys()
    {
        return [];
    }
}

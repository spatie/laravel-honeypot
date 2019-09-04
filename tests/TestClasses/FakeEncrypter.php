<?php

namespace Spatie\Honeypot\Tests\TestClasses;

use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\Encrypter;

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
}

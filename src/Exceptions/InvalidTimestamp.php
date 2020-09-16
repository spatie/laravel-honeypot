<?php

namespace Spatie\Honeypot\Exceptions;

use Exception;

class InvalidTimestamp extends Exception
{
    public static function make(string $timestamp)
    {
        return new static("Timestamp {$timestamp} is invalid");
    }
}

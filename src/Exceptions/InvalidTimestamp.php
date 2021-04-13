<?php

namespace Spatie\Honeypot\Exceptions;

use Exception;

class InvalidTimestamp extends Exception
{
    public static function make(string $timestamp): self
    {
        return new static("Timestamp {$timestamp} is invalid");
    }
}

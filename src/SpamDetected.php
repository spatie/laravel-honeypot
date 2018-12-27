<?php

namespace Spatie\Honeypot;

use Illuminate\Http\Request;

class SpamDetected
{
    /** @var \Illuminate\Http\Request */
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}

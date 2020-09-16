<?php

namespace Spatie\Honeypot\Events;

use Illuminate\Http\Request;

class SpamDetectedEvent
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}

<?php

namespace Spatie\Honeypot\SpamResponder;

use Closure;
use Illuminate\Http\Request;

interface SpamResponder
{
    public function respond(Request $request, Closure $next);
}

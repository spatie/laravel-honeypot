<?php

namespace Spatie\Honeypot\SpamResponder;

use Closure;
use Illuminate\Http\Request;

class BlankPageResponse implements SpamResponse
{
    public function respond(Request $request, Closure $next)
    {
        return response('');
    }
}
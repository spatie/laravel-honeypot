<?php

namespace Spatie\Honeypot\SpamResponder;

use Closure;
use Illuminate\Http\Request;

class BlankPageResponder implements SpamResponder
{
    public function respond(Request $request, Closure $next)
    {
        return response('');
    }
}

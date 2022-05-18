<?php

namespace Spatie\Honeypot;

use Closure;
use Illuminate\Http\Request;
use Spatie\Honeypot\Exceptions\SpamException;
use Spatie\Honeypot\SpamResponder\SpamResponder;

class ProtectAgainstSpam
{
    public function __construct(
        protected SpamResponder $spamResponder
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            app(SpamProtection::class)->check($request);

            return $next($request);
        } catch (SpamException) {
            return $this->spamResponder->respond($request, $next);
        }
    }
}

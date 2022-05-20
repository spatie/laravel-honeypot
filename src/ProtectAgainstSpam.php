<?php

namespace Spatie\Honeypot;

use Closure;
use Illuminate\Http\Request;
use Spatie\Honeypot\Events\SpamDetectedEvent;
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
        if (! $request->isMethod('POST')) {
            return $next($request);
        }

        try {
            app(SpamProtection::class)->check($request->all());

            return $next($request);
        } catch (SpamException) {
            event(new SpamDetectedEvent($request));

            return $this->spamResponder->respond($request, $next);
        }
    }
}

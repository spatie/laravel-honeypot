<?php

namespace Spatie\Honeypot;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Honeypot\SpamResponder\SpamResponder;

class ProtectAgainstSpam
{
    /** @var \Spatie\Honeypot\SpamResponder\SpamResponder */
    protected $spamResponder;

    public function __construct(SpamResponder $spamResponder)
    {
        $this->spamResponder = $spamResponder;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! config('honeypot.enabled')) {
            return $next($request);
        }

        $honeypotValue = $request->get(config('honeypot.name_field_name'));

        if (! empty($honeypotValue)) {
            return $this->respondToSpam($request, $next);
        }

        if ($validFrom = $request->get(config('honeypot.valid_from_field_name'))) {
            if ((new EncryptedTime($validFrom))->isFuture()) {
                return $this->respondToSpam($request, $next);
            }
        }

        return $next($request);
    }

    protected function respondToSpam(Request $request, Closure $next): Response
    {
        event(new SpamDetected($request));

        return $this->spamResponder->respond($request, $next);
    }
}

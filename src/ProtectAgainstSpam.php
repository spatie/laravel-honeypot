<?php

namespace Spatie\Honeypot;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PharIo\Version\SpecificMajorAndMinorVersionConstraint;
use Spatie\Honeypot\EncrypedTime;
use Spatie\Honeypot\SpamResponder\SpamResponder;
use Spatie\ResponseCache\ResponseCache;
use Spatie\ResponseCache\Events\CacheMissed;
use Symfony\Component\HttpFoundation\Response;
use Spatie\ResponseCache\Events\ResponseCacheHit;

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
        if (!config('honeypot.enabled')) {
            return $next($request);
        }

        $honeypotValue = $request->get(config('honeypot.name_field_name'));

        if (! empty($honeypotValue)) {
            return $this->respondToSpam($request, $next);
        }

        if ($validFrom = $request->get(config('honeypot.valid_from_field_name'))) {
            if ((new EncrypedTime($validFrom))->isFuture()) {
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

<?php

namespace Spatie\Honeypot;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PharIo\Version\SpecificMajorAndMinorVersionConstraint;
use Spatie\Honeypot\EncrypedTime;
use Spatie\Honeypot\Exceptions\SpamDetected;
use Spatie\Honeypot\SpamResponder\SpamResponse;
use Spatie\ResponseCache\ResponseCache;
use Spatie\ResponseCache\Events\CacheMissed;
use Symfony\Component\HttpFoundation\Response;
use Spatie\ResponseCache\Events\ResponseCacheHit;

class ProtectAgainstSpam
{
    /** @var \Spatie\Honeypot\SpamResponder\SpamResponse */
    protected $spamResponder;

    public function __construct(SpamResponse $spamResponder)
    {
        $this->spamResponder = $spamResponder;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!config('honeypot.enabled')) {
            return $next($request);
        }

        if ($request->has('honeypot.name_field_name')) {
            return $this->respondToSpam($request, $next);
        }

        if ($validFrom = $request->get('honeypot.valid_from_field_name')) {
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

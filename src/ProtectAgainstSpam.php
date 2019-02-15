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

        if (! $request->isMethod('POST')) {
            return $next($request);
        }

        $nameFieldName = config('honeypot.name_field_name');

        if (config('honeypot.randomize_name_field_name')) {
            $nameFieldName = $this->getRandomizedNameFieldName($nameFieldName, $request->all());
        }

        $honeypotValue = $request->get($nameFieldName);

        if (is_null($nameFieldName)) {
            return $this->respondToSpam($request, $next);
        }

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

    private function getRandomizedNameFieldName($nameFieldName, $requestFields):?String
    {
        return collect($requestFields)->filter(function ($value, $key) use ($nameFieldName) {
            return starts_with($key, $nameFieldName);
        })->keys()->first();
    }

    protected function respondToSpam(Request $request, Closure $next): Response
    {
        event(new SpamDetected($request));

        return $this->spamResponder->respond($request, $next);
    }
}

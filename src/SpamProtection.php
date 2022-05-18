<?php

namespace Spatie\Honeypot;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Honeypot\Events\SpamDetectedEvent;
use Spatie\Honeypot\Exceptions\InvalidTimestamp;
use Spatie\Honeypot\Exceptions\SpamException;

class SpamProtection
{
    /**
     * @throws SpamException
     */
    public function check(?Request $request = null): void
    {
        $request ??= request();

        if (! config('honeypot.enabled')) {
            return;
        }

        if (! $request->isMethod('POST')) {
            return;
        }

        $nameFieldName = config('honeypot.name_field_name');

        if (config('honeypot.randomize_name_field_name')) {
            $nameFieldName = $this->getRandomizedNameFieldName($nameFieldName, $request->all());
        }

        if (! $this->shouldCheckHoneypot($request, $nameFieldName)) {
            return;
        }

        if (! $request->has($nameFieldName)) {
            $this->spamDetected($request);
        }

        $honeypotValue = $request->get($nameFieldName);

        if (! empty($honeypotValue)) {
            $this->spamDetected($request);
        }

        if (! config('honeypot.valid_from_timestamp')) {
            return;
        }

        $validFrom = $request->get(config('honeypot.valid_from_field_name'));

        if (! $validFrom) {
            $this->spamDetected($request);
        }

        try {
            $time = new EncryptedTime($validFrom);
        } catch (InvalidTimestamp) {
            $time = null;
        }

        if (! $time || $time->isFuture()) {
            $this->spamDetected($request);
        }
    }

    protected function getRandomizedNameFieldName($nameFieldName, $requestFields): ?string
    {
        return collect($requestFields)
            ->filter(fn ($value, $key) => Str::startsWith($key, $nameFieldName))
            ->keys()
            ->first();
    }

    /**
     * @throws SpamException
     */
    protected function spamDetected(Request $request): void
    {
        event(new SpamDetectedEvent($request));

        throw new SpamException();
    }

    private function shouldCheckHoneypot(Request $request, ?string $nameFieldName): bool
    {
        if (config('honeypot.honeypot_fields_required_for_all_forms') == true) {
            return true;
        }

        return $request->has($nameFieldName) || $request->has(config('honeypot.valid_from_field_name'));
    }
}

<?php

namespace Spatie\Honeypot\Concerns;

use Illuminate\Http\Request;
use Spatie\Honeypot\Exceptions\SpamException;
use Spatie\Honeypot\SpamProtection;

trait UsesSpamProtection
{
    protected function protectAgainstSpam(?Request $request = null): void
    {
        try {
            app(SpamProtection::class)->check($request);
        } catch (SpamException) {
            abort(403, 'Spam detected.');
        }
    }
}

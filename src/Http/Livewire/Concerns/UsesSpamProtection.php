<?php

namespace Spatie\Honeypot\Http\Livewire\Concerns;

use Livewire\Component;
use ReflectionProperty;
use Spatie\Honeypot\Events\SpamDetectedEvent;
use Spatie\Honeypot\Exceptions\SpamException;
use Spatie\Honeypot\SpamProtection;

/** @mixin Component */
trait UsesSpamProtection
{
    public function guessHoneypotDataProperty(): ?HoneypotData
    {
        $props = (new \ReflectionClass($this))
            ->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($props as $prop) {
            if ($prop->getType()?->getName() === HoneypotData::class) {
                return $prop->getValue($this);
            }
        }

        return null;
    }

    protected function protectAgainstSpam(): void
    {
        $honeypotData = $this->guessHoneypotDataProperty();

        if (is_null($honeypotData)) {
            throw new \Exception("Livewire component requires a `HoneypotData` property.");
        }

        try {
            app(SpamProtection::class)->check($honeypotData->toArray());
        } catch (SpamException) {
            event(new SpamDetectedEvent(request()));

            abort(403, 'Spam detected.');
        }
    }
}

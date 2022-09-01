<?php

namespace Spatie\Honeypot;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Honeypot\Exceptions\InvalidTimestamp;
use Spatie\Honeypot\Exceptions\SpamException;

class SpamProtection
{
	public function check(Collection|array $requestFields): void
	{
		if (!config('honeypot.enabled')) {
			return;
		}

		$requestFields = Collection::wrap($requestFields);

		$nameFieldName = config('honeypot.name_field_name');

		if (config('honeypot.randomize_name_field_name')) {
			$nameFieldName = $this->getRandomizedNameFieldName($nameFieldName, $requestFields);
		}

		if (!$this->shouldCheckHoneypot($requestFields, $nameFieldName)) {
			return;
		}

		if (!$requestFields->has($nameFieldName)) {
			$this->storeHoneypotLog(request(), $requestFields);
			throw new SpamException();
		}

		$honeypotValue = $requestFields->get($nameFieldName);

		if (!empty($honeypotValue)) {
			$this->storeHoneypotLog(request(), $requestFields);
			throw new SpamException();
		}

		if (!config('honeypot.valid_from_timestamp')) {
			return;
		}

		$validFrom = $requestFields->get(config('honeypot.valid_from_field_name'));

		if (!$validFrom) {
			$this->storeHoneypotLog(request(), $requestFields);
			throw new SpamException();
		}

		try {
			$time = new EncryptedTime($validFrom);
		} catch (InvalidTimestamp) {
			$time = null;
		}

		if (!$time || $time->isFuture()) {
			$this->storeHoneypotLog(request(), $requestFields);
			throw new SpamException();
		}
	}

	protected function storeHoneypotLog(Request $request, Collection|array $requestFields)
	{
		if (config('honeypot.spam_protection')) {
			HoneypotLog::create([
				'method' => $request->getMethod(),
				'url' => $request->getUri(),
				'form_payload' => json_encode($requestFields),
				'headers' => (string)$request->headers
			]);
		}
	}

	protected function getRandomizedNameFieldName(string $nameFieldName, Collection $requestFields): ?string
	{
		return $requestFields
			->filter(fn($value, $key) => Str::startsWith($key, $nameFieldName))
			->keys()
			->first();
	}

	private function shouldCheckHoneypot(Collection $requestFields, ?string $nameFieldName): bool
	{
		if (config('honeypot.honeypot_fields_required_for_all_forms') === true) {
			return true;
		}

		return $requestFields->has($nameFieldName)
			|| $requestFields->has(config('honeypot.valid_from_field_name'));
	}
}

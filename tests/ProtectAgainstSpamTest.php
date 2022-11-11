<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use function PHPUnit\Framework\assertEquals;
use Spatie\Honeypot\EncryptedTime;
use Spatie\Honeypot\ProtectAgainstSpam;

use Spatie\TestTime\TestTime;

beforeEach(function () {
    TestTime::freeze('Y-m-d H:i:s', '2019-01-01 00:00:00');

    TestResponse::macro('assertPassedSpamProtection', function () {
        $this
            ->assertSuccessful()
            ->assertSee('ok');

        return $this;
    });

    TestResponse::macro('assertDidNotPassSpamProtection', function () {
        $content = $this
            ->assertSuccessful()
            ->baseResponse->content();

        assertEquals('', $content, 'The request unexpectedly passed spam protection.');

        return $this;
    });

    Route::any('test', function () {
        return 'ok';
    })->middleware(ProtectAgainstSpam::class);
});

test('requests that not use the honeypot fields succeed without random name')
    ->tap(fn () => config()->set('honeypot.randomize_name_field_name', false))
    ->post('test')
    ->assertPassedSpamProtection();

test('requests that do not use the honeypot fields succeed with random name')
    ->tap(fn () => config()->set('honeypot.randomize_name_field_name', true))
    ->post('test')
    ->assertPassedSpamProtection();

test('requests that do not use the honeypot fields do not succeed without random name when missing fields enabled')
    ->tap(fn () => config()->set('honeypot.randomize_name_field_name', false))
    ->tap(fn () => config()->set('honeypot.honeypot_fields_required_for_all_forms', true))
    ->post('test')
    ->assertDidNotPassSpamProtection();

test('requests that do not use the honeypot fields do not succeed with random name when missing fields enabled')
    ->tap(fn () => config()->set('honeypot.randomize_name_field_name', true))
    ->tap(fn () => config()->set('honeypot.honeypot_fields_required_for_all_forms', true))
    ->post('test')
    ->assertDidNotPassSpamProtection();

test('requests that post an empty value for the honeypot name field do succeed', function () {
    $nameField = config('honeypot.name_field_name');
    $validFromField = config('honeypot.valid_from_field_name');
    $validFrom = EncryptedTime::create(now());

    $this
        ->post('test', [$nameField => '', $validFromField => $validFrom])
        ->assertPassedSpamProtection();
});

test('requests that post the honeypot name field with content do not succeed', function () {
    $nameField = config('honeypot.name_field_name');

    $this
        ->post('test', [$nameField => 'value'])
        ->assertDidNotPassSpamProtection();
});

test('requests will always succeed when the package is not enabled', function () {
    config()->set('honeypot.enabled', false);

    $nameField = config('honeypot.name_field_name');

    $this
        ->post('test', [$nameField => 'value'])
        ->assertPassedSpamProtection();
});

test('requests will always succeed when the method is not POST', function () {
    $nameField = config('honeypot.name_field_name');
    $attributes = [$nameField => 'value'];

    $this->get('test', $attributes)->assertPassedSpamProtection();
    $this->put('test', $attributes)->assertPassedSpamProtection();
    $this->patch('test', $attributes)->assertPassedSpamProtection();
    $this->delete('test', $attributes)->assertPassedSpamProtection();
});

test('submissions that are posted too son will be marked as spam', function () {
    $nameField = config('honeypot.name_field_name');
    $validFromField = config('honeypot.valid_from_field_name');
    $validFrom = EncryptedTime::create(now()->addSecond());

    $this
        ->post('test', [$nameField => '', $validFromField => $validFrom])
        ->assertDidNotPassSpamProtection();
});

test('submissions that are not posted after or on valid form will not be marked as spam when timestamp check is disabled', function () {
    config()->set('honeypot.valid_from_timestamp', false);
    $nameField = config('honeypot.name_field_name');
    $validFromField = config('honeypot.valid_from_field_name');
    $validFrom = EncryptedTime::create(now());

    $this
        ->post('test', [$nameField => '', $validFromField => $validFrom])
        ->assertPassedSpamProtection();
});

test('submissions that are posted after or on valid form will not be marked as spam when timestamp check is enabled', function () {
    config()->set('honeypot.valid_from_timestamp', true);
    $nameField = config('honeypot.name_field_name');
    $validFromField = config('honeypot.valid_from_field_name');
    $validFrom = EncryptedTime::create(now());

    $this
        ->post('test', [$nameField => '', $validFromField => $validFrom])
        ->assertPassedSpamProtection();
});

test('submissions taht are posted after or on valid form will not be marked as spam', function () {
    $nameField = config('honeypot.name_field_name');
    $validFromField = config('honeypot.valid_from_field_name');
    $validFrom = EncryptedTime::create(now());

    $this
        ->post('test', [$nameField => '', $validFromField => $validFrom])
        ->assertPassedSpamProtection();
});

test('submission with random generated name for the honeypot name field do succeed', function () {
    config()->set('honeypot.randomize_name_field_name', true);

    $nameField = config('honeypot.name_field_name');
    $validFromField = config('honeypot.valid_from_field_name');
    $validFrom = EncryptedTime::create(now());

    $this
        ->post('test', [$nameField . '-' . Str::random() => null, $validFromField => $validFrom])
        ->assertPassedSpamProtection();
});

test('submissions that are posted with invalid payload will be marked as spam', function () {
    config()->set('honeypot.randomize_name_field_name', true);

    $nameField = config('honeypot.name_field_name') . Str::random();
    $validFromField = config('honeypot.valid_from_field_name');

    $validFrom = 'SomeRandomString';

    $this
        ->post('test', [$nameField => '', $validFromField => $validFrom])
        ->assertDidNotPassSpamProtection();
});

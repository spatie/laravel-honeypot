<?php

use Spatie\Honeypot\Honeypot;

test('honeypot setup returns enabled true if true in config', function () {
    config()->set('honeypot.enabled', true);

    app(Honeypot::class)->toArray()['enabled'];

    expect(app(Honeypot::class)->toArray()['enabled'])->toBeTrue();
});

test('honeypot setup returns enabled false if false is in config')
    ->tap(fn () => config()->set('honeypot.enabled', false))
    ->expect(fn () => app(Honeypot::class)->toArray()['enabled'])
    ->toBeFalse();

test('honeypot setup returns correct `name_field_name` when randomize name field name is `false`')
    ->tap(function () {
        config()->set('honeypot.name_field_name', 'test_field');
        config()->set('honeypot.randomize_name_field_name', false);
    })
    ->expect(fn () => app(Honeypot::class)->toArray()['nameFieldName'])
    ->toEqual('test_field');

test(
    'honeypot setup returns correct `name_field_name` when randomize name field name is `true`',
    function () {
        config()->set('honeypot.name_field_name', 'test_field');
        config()->set('honeypot.randomize_name_field_name', true);

        $actualNameFieldName = app(Honeypot::class)->toArray()['nameFieldName'];

        expect($actualNameFieldName)
            ->toStartWith('test_field_')
            ->toBeGreaterThan(11);
    }
);

test('honeypot setup returns correct valid from field name', function () {
    config()->set('honeypot.valid_from_field_name', 'test_from_field');

    $actualValidFromFieldName = app(Honeypot::class)->toArray()['validFromFieldName'];

    expect($actualValidFromFieldName)->toEqual('test_from_field');
});

test('honeypot setup returns an encrypted time', function () {
    $actualValue = app(Honeypot::class)->toArray()['encryptedValidFrom'];

    expect(strlen($actualValue))->toBeGreaterThan(1);
});

<?php

use Carbon\CarbonImmutable;
use Illuminate\Support\DateFactory;
use Spatie\Honeypot\Tests\TestClasses\FakeEncrypter;
use function Spatie\Snapshots\assertMatchesSnapshot;

use Spatie\TestTime\TestTime;

beforeEach(function () {
    $this->swap('encrypter', new FakeEncrypter());

    config()->set('honeypot.randomize_name_field_name', false);
});

test('the honeypot Blade directive renders correctly', function () {
    TestTime::freeze('Y-m-d H:i:s', '2019-01-01 00:00:00');

    $renderedView = view('honeypot')->render();

    assertMatchesSnapshot($renderedView);
});

test('the honeypot Blade directive renders correctly when using CarbonImmutable', function () {
    DateFactory::use(CarbonImmutable::class);
    TestTime::freeze('Y-m-d H:i:s', '2019-01-01 00:00:00');

    $renderedView = view('honeypot')->render();

    assertMatchesSnapshot($renderedView);

    DateFactory::use(DateFactory::DEFAULT_CLASS_NAME);
});

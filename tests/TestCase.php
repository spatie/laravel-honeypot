<?php

namespace Spatie\Honeypot\Tests;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\View;
use Spatie\Honeypot\HoneypotServiceProvider;
use Spatie\Honeypot\Tests\TestClasses\FakeEncrypter;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithContainer;

    public function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__.'/views');

        $this->setNow(2019, 1, 1);

        $this->swap('encrypter', new FakeEncrypter());
    }

    protected function getPackageProviders($app)
    {
        return [HoneypotServiceProvider::class];
    }

    protected function setNow($year, int $month = 1, int $day = 1)
    {
        $newNow = ($year instanceof Carbon || $year instanceof CarbonInterface)
            ? $year->copy()
            : Carbon::createFromDate($year, $month, $day);

        $newNow = $newNow->startOfDay();

        Carbon::setTestNow($newNow);

        if (class_exists(CarbonImmutable::class)) {
            CarbonImmutable::setTestNow($newNow);
        }
    }
}

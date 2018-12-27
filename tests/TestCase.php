<?php

namespace Spatie\Honeypot\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Support\Facades\View;
use Spatie\Honeypot\HoneypotServiceProvider;
use Spatie\Honeypot\Tests\TestClasses\FakeEncrypter;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithContainer;

    public function setUp()
    {
        parent::setUp();

        View::addLocation(__DIR__ . '/views');

        $this->setNow(2019, 1, 1);

        $this->swap('encrypter', new FakeEncrypter());
    }

    protected function getPackageProviders($app)
    {
        return [HoneypotServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');


    }

    protected function setNow($year, int $month = 1, int $day = 1)
    {
        $newNow = $year instanceof Carbon
            ? $year
            : Carbon::createFromDate($year, $month, $day);

        $newNow = $newNow->startOfDay();

        Carbon::setTestNow($newNow);
    }

    protected function progressTime(int $minutes)
    {
        $newNow = now()->copy()->addMinutes($minutes);

        Carbon::setTestNow($newNow);

        return $this;
    }
}

<?php

namespace Spatie\Honeypot\Tests;

use Illuminate\Support\Facades\View;
use Spatie\Honeypot\HoneypotServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

        View::addLocation(__DIR__ . '/views');
    }

    protected function getPackageProviders($app)
    {
        return [HoneypotServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }
}

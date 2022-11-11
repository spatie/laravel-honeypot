<?php

namespace Spatie\Honeypot\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Support\Facades\View;
use Livewire\LivewireServiceProvider;
use Spatie\Honeypot\HoneypotServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithContainer;

    protected $testNow = true;

    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__ . '/views');

        config()->set('app.key', 'base64:05V7tNPZKeo4DB3PT/Xzgw6qAKxVTAjUWWZ9YrzpBc0=');
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            HoneypotServiceProvider::class,
        ];
    }
}

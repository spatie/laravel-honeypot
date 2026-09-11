<?php

namespace Spatie\Honeypot\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Spatie\Honeypot\HoneypotServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithContainer;

    protected $testNow = true;

    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__ . '/views');

        Blade::directive('cspNonce', fn () => 'nonce="test-nonce"');

        config()->set('app.key', 'base64:05V7tNPZKeo4DB3PT/Xzgw6qAKxVTAjUWWZ9YrzpBc0=');
    }

    protected function getPackageProviders($app)
    {
        $providers = [
            HoneypotServiceProvider::class,
        ];

        if (class_exists(\Livewire\LivewireServiceProvider::class)) {
            $providers[] = \Livewire\LivewireServiceProvider::class;
        }

        return $providers;
    }
}

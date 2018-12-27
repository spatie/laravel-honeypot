<?php

namespace Spatie\Honeypot\Tests;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

class ProtectAgainstSpamTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        TestResponse::macro('assertPassedSpamProtection', function() {
            $this
                ->assertSuccessful()
                ->assertSee('ok');

            return $this;
        });

        TestResponse::macro('assertDidNotPassSpamProtection', function() {
            $content = $this
                ->assertSuccessful()
                ->baseResponse->content();

            TestCase::assertEquals('', $content, 'The request unexpectately passed spam protection.');

            return $this;
        });

        Route::post('test', function() {
            return 'ok';
        })->middleware(ProtectAgainstSpam::class);
    }

    /** @test */
    public function requests_that_not_use_the_honeypot_fields_succeed()
    {
        $this
            ->post('test')
            ->assertPassedSpamProtection();
    }

    /** @test */
    public function requests_that_post_an_empty_value_for_the_honeypot_name_field_do_succeed()
    {
        $nameField = config('honeypot.name_field_name');

        $this
            ->post('test', [$nameField => ''])
            ->assertPassedSpamProtection();
    }

    /** @test */
    public function requests_that_post_the_honeypot_name_field_with_content_do_not_succeed()
    {
        $nameField = config('honeypot.name_field_name');

        $this
            ->post('test', [$nameField => 'value'])
            ->assertDidNotPassSpamProtection();
    }

    /** @test */
    public function requests_will_always_succeed_when_the_package_is_not_enabled()
    {
        config()->set('honeypot.enabled', false);

        $nameField = config('honeypot.name_field_name');

        $this
            ->post('test', [$nameField => 'value'])
            ->assertPassedSpamProtection();
    }
}
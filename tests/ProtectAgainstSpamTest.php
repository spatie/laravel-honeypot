<?php

namespace Spatie\Honeypot\Tests;

use Illuminate\Support\Str;
use Spatie\Honeypot\EncryptedTime;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use Illuminate\Foundation\Testing\TestResponse;

class ProtectAgainstSpamTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

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

            TestCase::assertEquals('', $content, 'The request unexpectately passed spam protection.');

            return $this;
        });

        Route::any('test', function () {
            return 'ok';
        })->middleware(ProtectAgainstSpam::class);
    }

    /** @test */
    public function requests_that_not_use_the_honeypot_fields_succeed()
    {
        config()->set('honeypot.randomize_name_field_name', false);

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

    /** @test */
    public function requests_will_always_succeed_when_the_method_is_not_POST()
    {
        $nameField = config('honeypot.name_field_name');
        $attributes = [$nameField => 'value'];

        $this->get('test', $attributes)->assertPassedSpamProtection();
        $this->put('test', $attributes)->assertPassedSpamProtection();
        $this->patch('test', $attributes)->assertPassedSpamProtection();
        $this->delete('test', $attributes)->assertPassedSpamProtection();
    }

    /** @test */
    public function submissions_that_are_posted_too_soon_will_be_marked_as_spam()
    {
        $nameField = config('honeypot.name_field_name');
        $validFromField = config('honeypot.valid_from_field_name');
        $validFrom = EncryptedTime::create(now()->addSecond());

        $this
            ->post('test', [$nameField => '', $validFromField => $validFrom])
            ->assertDidNotPassSpamProtection();
    }

    /** @test */
    public function submissions_that_are_posted_after_or_on_valid_from_will_not_be_marked_as_spam()
    {
        $nameField = config('honeypot.name_field_name');
        $validFromField = config('honeypot.valid_from_field_name');
        $validFrom = EncryptedTime::create(now());

        $this
            ->post('test', [$nameField => '', $validFromField => $validFrom])
            ->assertPassedSpamProtection();
    }

    /** @test */
    public function submission_with_random_generated_name_for_the_honeypot_name_field_do_succeed()
    {
        config()->set('honeypot.randomize_name_field_name', true);

        $nameField = config('honeypot.name_field_name');

        $this
            ->post('test', [$nameField.'-'.Str::random() => null])
            ->assertPassedSpamProtection();
    }

    /** @test */
    public function submission_with_random_generated_name_without_correct_prefix_will_be_marked_as_spam()
    {
        config()->set('honeypot.randomize_name_field_name', true);

        $this
            ->post('test')
            ->assertDidNotPassSpamProtection();
    }
}

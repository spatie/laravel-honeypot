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
        });

        Route::get('test', function() {
            return view('honeypot');
        })->middleware(ProtectAgainstSpam::class);

        Route::post('test', function() {
            return 'ok';
        })->middleware(ProtectAgainstSpam::class);
    }

    /** @test */
    public function requests_without_spam_protection_pass()
    {
        $this
            ->visit('test');
    }
}
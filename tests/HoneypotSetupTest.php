<?php

namespace Spatie\Honeypot\Tests;

use Illuminate\Support\Str;
use Spatie\Honeypot\HoneypotSetup;

class HoneypotSetupTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function honeypot_setup_returns_enabled_true_if_true_in_config()
    {
        config()->set('honeypot.enabled', true);

        $this->assertTrue(HoneypotSetup::get()['enabled']);
    }

    /** @test */
    public function honeypot_setup_returns_enabled_false_if_false_in_config()
    {
        config()->set('honeypot.enabled', false);

        $this->assertFalse(HoneypotSetup::get()['enabled']);
    }

    /** @test */
    public function honeypot_setup_returns_correct_name_field_name_when_randomize_name_field_name_is_false()
    {
        config()->set('honeypot.name_field_name', 'test_field');
        config()->set('honeypot.randomize_name_field_name', false);
        $this->assertEquals(HoneypotSetup::get()['nameFieldName'], 'test_field');
    }

    /** @test */
    public function honeypot_setup_returns_correct_name_field_name_when_randomize_name_field_name_is_true()
    {
        config()->set('honeypot.name_field_name', 'test_field');
        config()->set('honeypot.randomize_name_field_name', true);

        $nameFieldName = HoneypotSetup::get()['nameFieldName'];

        $this->assertTrue(Str::of($nameFieldName)->startsWith('test_field_'));

        $this->assertTrue(Str::of($nameFieldName)->length() > 11);
    }

    /** @test */
    public function honeypot_setup_returns_correct_valid_from_field_name()
    {
        config()->set('honeypot.valid_from_field_name', 'test_from_field');

        $this->assertEquals(HoneypotSetup::get()['validFromFieldName'], 'test_from_field');
    }

    /** @test */
    public function honeypot_setup_returns_an_encrypted_time()
    {
        $this->assertTrue(Str::of(HoneypotSetup::get()['encryptedValidFrom'])->length() > 0);
    }
}

# Preventing spam submitted through forms

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-honeypot.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-honeypot)
![Test Status](https://img.shields.io/github/workflow/status/spatie/laravel-honeypot/run-tests?label=tests)
![Code Style Status](https://img.shields.io/github/workflow/status/spatie/laravel-honeypot/Check%20&%20fix%20styling?label=code%20style)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-honeypot.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-honeypot)

When adding a form to a public site, there's a risk that spam bots will try to submit it with fake values. Luckily, the majority of these bots are pretty dumb. You can thwart most of them by adding an invisible field to your form that should never contain a value when submitted. Such a field is called a honeypot. These spam bots will just fill all fields, including the honeypot.

When a submission comes in with a filled honeypot field, this package will discard that request. 
On top of that this package also checks how long it took to submit the form. This is done using a timestamp in another invisible field. If the form was submitted in a ridiculously short time, the anti spam will also be triggered.

After installing this package, all you need to do is to add the `x-honeypot` Blade component to your form.

```html
<form method="POST">
    <x-honeypot />
    <input name="myField" type="text">
</form>
```

The package also supports manually passing the necessary values to your view layer, so you can easily add honeypot fields to your [Inertia](https://inertiajs.com) powered app.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-honeypot.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-honeypot)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Video tutorial

In [this video](https://vimeo.com/381197983), which is part of the [Mailcoach](https://mailcoach.app) video course, you can see how the package can be installed and used.

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-honeypot
```

Optionally, you can publish the config file of the package.

```bash
php artisan vendor:publish --provider="Spatie\Honeypot\HoneypotServiceProvider" --tag=config
```

This is the content of the config file that will be published at `config/honeypot.php`:

```php
use Spatie\Honeypot\SpamResponder\BlankPageResponder;

return [
    /*
     * Here you can specify name of the honeypot field. Any requests that submit a non-empty
     * value for this name will be discarded. Make sure this name does not
     * collide with a form field that is actually used.
     */
    'name_field_name' => env('HONEYPOT_NAME', 'my_name'),

    /*
     * When this is activated there will be a random string added
     * to the name_field_name. This improves the
     * protection against bots.
     */
    'randomize_name_field_name' => env('HONEYPOT_RANDOMIZE', true),

    /*
     * This field contains the name of a form field that will be used to verify
     * if the form wasn't submitted too quickly. Make sure this name does not
     * collide with a form field that is actually used.
     */
    'valid_from_field_name' => env('HONEYPOT_VALID_FROM', 'valid_from'),

    /*
     * If the form is submitted faster than this amount of seconds
     * the form submission will be considered invalid.
     */
    'amount_of_seconds' => env('HONEYPOT_SECONDS', 1),

    /*
     * This class is responsible for sending a response to requests that
     * are detected as being spammy. By default a blank page is shown.
     *
     * A valid responder is any class that implements
     * `Spatie\Honeypot\SpamResponder\SpamResponder`
     */
    'respond_to_spam_with' => BlankPageResponder::class,

    /*
     * When activated, requests will be checked if honeypot fields are missing,
     * if so the request will be stamped as spam. Be careful! When using the
     * global middleware be sure to add honeypot fields to each form.
     */
    'honeypot_fields_required_for_all_forms' => false,

    /*
     * This switch determines if the honeypot protection should be activated.
     */
    'enabled' => env('HONEYPOT_ENABLED', true),
];
```
  
## Usage

First, you must add the `x-honeypot` Blade component to any form you wish to protect.

```php
<form method="POST" action="{{ route('contactForm.submit') }}")>
    <x-honeypot />
    <input name="myField" type="text">
</form>
```

Alternatively, you can use the `@honeypot` Blade directive:

```php
<form method="POST" action="{{ route('contactForm.submit') }}")>
    @honeypot
    <input name="myField" type="text">
</form>
```

Using either the Blade component or directive will add two fields: `my_name` and `my_time` (you can change the names in the config file).

Next, you must use the `Spatie\Honeypot\ProtectAgainstSpam` middleware in the route that handles the form submission. This middleware will intercept any request that submits a non empty value for the key named `my_name`. It will also intercept the request if it is submitted faster than the encrypted timestamp that the package generated in `my_time`.

```php
use App\Http\Controllers\ContactFormSubmissionController;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::post([ContactFormSubmissionController::class, 'create'])->middleware(ProtectAgainstSpam::class);
```

If you want to integrate the `Spatie\Honeypot\ProtectAgainstSpam` middleware with Laravel's built in authentication routes, wrap the `Auth::routes();` declaration with the appropriate middleware group (make sure to add the `@honeypot` directive to the authentication forms).

```php
use Spatie\Honeypot\ProtectAgainstSpam;

Route::middleware(ProtectAgainstSpam::class)->group(function() {
    Auth::routes();
});
```

If your app has a lot of forms handled by many different controllers, you could opt to register it as global middleware.

```php
// inside app\Http\Kernel.php

protected $middleware = [
   // ...
   \Spatie\Honeypot\ProtectAgainstSpam::class,
];
```

### Usage in Inertia 

When using Inertia you must manually pass the values used in the honeypot fields. Here's an example:

```php
// in a controller
public function create(\Spatie\Honeypot\Honeypot $honeypot) 
{
    return inertia('contactform.show', [
        'honeypot' => $honeypot,
    ]);
}
```

Your front-end will get an `honeypot` object with these keys: `enabled`, `nameFieldName`, `validFromFieldName`, `encryptedValidFrom`.

Here's an example how these values could be rendered using Vue:

```html
<div v-if="enabled" name="{{ honeypot.nameFieldName }}_wrap" style="display:none;">
    <input name="{{ honeypot.nameFieldName }}" type="text" value="" id="{{ $nameFieldName }}">
    <input name="{{ honeypot.validFromFieldName }}" type="text" value="{{ honeypot.encryptedValidFrom }}">
</div>
```

### Disabling in testing

By default, any protected form that is submitted in faster than 1 second will be marked as spammy. When running end to end tests, which should run as fast as possible, you probably don't want this. 

To disable all honeypots in code, you can set the `enabled` config value to `false`.

```php
config()->set('honeypot.enabled', false)
```

### Customizing the response

When a spammy submission is detected, the package will show a blank page by default. You can customize this behaviour by writing your own `SpamResponse` and specifying its fully qualified class name in the `respond_to_spam_with` key of the `honeypot` config file.

A valid `SpamResponse` is any class that implements the `Spatie\Honeypot\SpamResponder\SpamResponder` interface. This is what that interface looks like:

```php
namespace Spatie\Honeypot\SpamResponder;

use Closure;
use Illuminate\Http\Request;

interface SpamResponder
{
    public function respond(Request $request, Closure $next);
}
```

Even though a spam responder's primary purpose is to respond to spammy requests, you could do other stuff there as well. You could for instance use the properties on `$request` to determine the source of the spam (maybe all requests come from the same IP) and put some logic to block that source altogether.

If the package wrongly determined that the request is spammy, you can generate the default response by passing the `$request` to the `$next` closure, like you would in a middleware.

```php
// in your spam responder
$regularResponse = $next($request)
```

### Customizing the generated honeypot fields

To customize output generated, you can publish the `honeypot` view with:

```php
php artisan vendor:publish --provider="Spatie\Honeypot\HoneypotServiceProvider" --tag=views
```

The view will be placed in `resources/views/vendor/honeypot/honeypotFormFields.blade.php`. This is the default content:

```php
@if($enabled)
    <div id="{{ $nameFieldName }}_wrap" style="display:none;">
        <input name="{{ $nameFieldName }}" type="text" value="" id="{{ $nameFieldName }}">
        <input name="{{ $validFromFieldName }}" type="text" value="{{ $encryptedValidFrom }}">
    </div>
@endif
```

### Events fired

Whenever spam is detected, the `Spatie\Honeypot|Events\SpamDetectedEvent` event is fired. It has the `$request` as a public property.

### Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Alternatives

If you need stronger spam protection, consider using [Google ReCaptcha](https://m.dotdev.co/google-recaptcha-integration-with-laravel-ad0f30b52d7d) or [Akismet](https://github.com/nickurt/laravel-akismet). 

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

This package was inspired by [the Honeypot package](https://github.com/msurguy/Honeypot) by [Maksim Surguy](https://github.com/msurguy).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

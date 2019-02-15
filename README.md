# Preventing spam submitted through forms

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-honeypot.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-honeypot)
[![Build Status](https://img.shields.io/travis/spatie/laravel-honeypot/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-honeypot)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-honeypot.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-honeypot)
[![StyleCI](https://github.styleci.io/repos/162617004/shield?branch=master)](https://github.styleci.io/repos/162617004)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-honeypot.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-honeypot)

When adding a form to a public site, there's a risk that spam bots will try to submit it with fake values. Luckily, the majority of these bots are pretty dumb. You can thwart most of them by adding an invisible field to your form that should never contain a value when submitted. Such a field is called a honeypot. These spam bots will just fill all fields, including the honeypot.

When a submission comes in with a filled honeypot field, this package will discard that request. 
On top of that this package also check how long it took to submit the form. This is done using a timestamp in another invisible field. If the form was submitted in a ridiculously short time, the anti spam will also be triggered.

After installing this package, all you need to do is to add a `@honeypot` Blade directive to your form.

```html
<form method="POST">
    @honeypot
    <input name="myField" type="text">
</form>
```

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
    'name_field_name' => 'my_name',

    /*
    * When this is activated there will be a random string added
    * to the name_field_name. This improves the
    * protection against bots.
    */
    'randomize_name_field_name' => true,

    /*
     * This field contains the name of a form field that will be use to verify
     * if the form wasn't submitted too quickly. Make sure this name does not
     * collide with a form field that is actually used.
     */
    'valid_from_field_name' => 'valid_from',

    /*
     * If the form is submitted faster then this amout of seconds
     * the form submission will be considered invalid.
     */
    'amount_of_seconds' => 1,

    /*
     * This class is responsible for sending a response to request that
     * are detected as being spammy. By default a blank page is shown.
     *
     * A valid responder is any class that implements
     * `Spatie\Honeypot\SpamResponder\SpamResponder`
     */
    'respond_to_spam_with' => BlankPageResponder::class,

    /*
     * This switch determines if the honeypot protection should be activated.
     */
    'enabled' => true,
];
```
  
## Usage

First, you must add the `@honeypot` blade directive to any form you wish to protect.

```php
<form method="POST" action="{{ action(App\Http\Controllers\ContactFormSubmissionController::class, 'create') }}")>
    @honeypot
    <input name="myField" type="text">
</form>
```

`@honeypot` will add two fields: `my_name` and `my_time` (you can change the names in the config file).

Next, you must use the `Spatie\Honeypot\ProtectAgainstSpam` middleware in the route that handles the form submission. This middleware will intercept any request that submits a non empty value for the key named `my_name`. It will also intercept the request if it is submitted faster than the encrypted timestamp that the package generated in `my_time`.

```php
use App\Http\Controllers\ContactFormSubmissionController;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::post([ContactFormSubmissionController::class, 'create'])->middleware(ProtectAgainstSpam::class);
```

If your app has a lot of forms handled by many different controllers, you could opt to register it as global middleware.

```php
// inside app\Http\Kernel.php

protected $middleware = [
   // ...
   \Spatie\Honeypot\ProtectAgainstSpam::class,
];
```

### Disabling in testing

By default, any protected form that is submitted in faster than 1 second will be marked as spammy. When running end to end tests, which should run as fast as possible, you probably don't want this. 

To disable all honeypots in code, you can set the `enabled` config value to `false`.

```php
config()->set('honeypot.enabled', false)
```

### Customizing the response

When a spammy submission is detected, the package will show a blank page by default. You can customize this behaviour by writing your own `SpamResponse` and specifying it's fully qualified class name in the `respond_to_spam_with` key of the `honeypot` config file.

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

Even though a spam responders primary purpose is to respond to spammy requests, you could do other stuff there as well. You could for instance use the properties on `$request` to determine the source of the spam (maybe all requests come from the same IP) and put some logic to block that source altogether.

If the package wrongly determined that the request is spammy, you can generate the default response by passing the `$request` to the `$next` closure, like you would in a middleware.

```php
// in your spam responder
$regularResponse = $next($request)
```

### Customizing the generated honeypot fields

To customize output generated by `@honeypot`, you can publish the `honeypot` view with:

```php
php artisan vendor:publish --provider="Spatie\Honeypot\HoneypotServiceProvider" --tag=views
```

The view will be placed in `resources/views/vendor/honeypot/honeypotFormFields.blade.php`. This is the default content:

```php
@if($enabled)
    <div id="{{ $nameFieldName }}_wrap" style="display:none;">
        <input name="{{ $nameFieldName }}" type="text" value="" id="my_name">
        <input name="{{ $validFromFieldName }}" type="text" value="{{ $encryptedValidFrom }}">
    </div>
@endif
```

### Events fired

Whenever spam is detected, the `Spatie\Honeypot\SpamDetected` event is fired. It has the `$request` as a public property.

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

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

This package was inspired by [the Honeypot package](https://github.com/msurguy/Honeypot) by [Maksim Surguy](https://github.com/msurguy).

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

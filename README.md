# Preventing spam submitted through forms

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-honeypot.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-honeypot)
![Test Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-honeypot/run-tests.yml?branch=main&label=Tests)
![Code Style Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-honeypot/php-cs-fixer.yml?branch=main&label=Code%20Style)
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
php artisan vendor:publish --provider="Spatie\Honeypot\HoneypotServiceProvider" --tag=honeypot-config
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
     * When this is activated, requests will be checked if
     * form is submitted faster than this amount of seconds
     */
    'valid_from_timestamp' => env('HONEYPOT_VALID_FROM_TIMESTAMP', true),
    
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
     * This class is responsible for applying all protection
     * rules for a request. By default uses `request()`.
     *
     * It throws the `Spatie\Honeypot\ExceptionsSpamException` if the
     * request is flagged as spam, or returns void if it succeeds.
     */
    'spam_protection' => \Spatie\Honeypot\SpamProtection::class,

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

```blade
<form method="POST" action="{{ route('contactForm.submit') }}")>
    <x-honeypot />
    <input name="myField" type="text">
</form>
```

Alternatively, you can use the `@honeypot` Blade directive:

```blade
<form method="POST" action="{{ route('contactForm.submit') }}")>
    @honeypot
    <input name="myField" type="text">
</form>
```

Using either the Blade component or directive will add two fields: `my_name` and `valid_from_timestamp` (you can change the names in the config file).

Next, you must use the `Spatie\Honeypot\ProtectAgainstSpam` middleware in the route that handles the form submission. This middleware will intercept any request that submits a non empty value for the key named `my_name`. It will also intercept the request if it is submitted faster than the encrypted timestamp that the package generated in `valid_from_timestamp`.

```php
use App\Http\Controllers\ContactFormSubmissionController;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::post('contact', [ContactFormSubmissionController::class, 'create'])->middleware(ProtectAgainstSpam::class);
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
<div v-if="honeypot.enabled" :name="`${honeypot.nameFieldName}_wrap`" style="display:none;">
    <input type="text" v-model="form[honeypot.nameFieldName]" :name="honeypot.nameFieldName" :id="honeypot.nameFieldName" />
    <input type="text" v-model="form[honeypot.validFromFieldName]" :name="honeypot.validFromFieldName" />
</div>
```

And then in your Vue component, add these values to your form data:

```javascript
props: ['honeypot'],

data() {
    return {
        form: this.$inertia.form({
            [this.honeypot.nameFieldName]: '',
            [this.honeypot.validFromFieldName]: this.honeypot.encryptedValidFrom,
        }),
    }
}
```

### Usage in Livewire

You can use this package to prevent spam submission to forms powered by Livewire.

First, add the `UsesSpamProtection` trait to your Livewire component:

```php
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;

class YourComponent extends Component
{
    use UsesSpamProtection;
```

Next, declare a `HoneypotData` property and call `protectAgainstSpam()` in the method that handles form submissions:

```php
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;

class YourComponent extends Component
{
    // ...
    
    public HoneypotData $extraFields;
    
    public function mount()
    {
        $this->extraFields = new HoneypotData();
    }
 
   
    public function submit(): void 
    {
        $this->protectAgainstSpam(); // if is spam, will abort the request
    
        User::create($request->all());
    }
}
```

Finally, use the `x-honeypot` in your Livewire Blade component:

```blade
<form method="POST" action="{{ route('contactForm.submit') }}")>
    <x-honeypot livewire-model="extraFields" />
    <input name="myField" type="text">
</form>
```

#### Usage in Volt functional syntax

To use this package in Volt functional syntax, return the `HoneypotData` property from the `guessHoneypotDataProperty` method.

```php
uses(UsesSpamProtection::class);

state([
    // ...
    'extraFields' => null,
]);

mount(function () {
    $this->extraFields = new HoneypotData();
});

$guessHoneypotDataProperty = fn () => $this->extraFields;

$submit = function () {
    $this->protectAgainstSpam(); // if is spam, will abort the request
    
    User::create($request->all());
};
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
php artisan vendor:publish --provider="Spatie\Honeypot\HoneypotServiceProvider" --tag=honeypot-views
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

Whenever spam is detected, the `Spatie\Honeypot\Events\SpamDetectedEvent` event is fired. It has the `$request` as a public property.

### Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Alternatives

If you need stronger spam protection, consider using [Google ReCaptcha](https://m.dotdev.co/google-recaptcha-integration-with-laravel-ad0f30b52d7d) or [Akismet](https://github.com/nickurt/laravel-akismet). 

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

This package was inspired by [the Honeypot package](https://github.com/msurguy/Honeypot) by [Maksim Surguy](https://github.com/msurguy).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

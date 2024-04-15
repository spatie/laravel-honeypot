# Changelog

All notable changes to `laravel-honeypot` will be documented in this file

## 4.5.2 - 2024-04-15

### What's Changed

* Fix Carbon 3 rawAddUnit type by @clementmas in https://github.com/spatie/laravel-honeypot/pull/131

### New Contributors

* @clementmas made their first contribution in https://github.com/spatie/laravel-honeypot/pull/131

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.5.1...4.5.2

## 4.5.1 - 2024-03-14

### What's Changed

* Added Carbon 3 to the requirements by @poldixd in https://github.com/spatie/laravel-honeypot/pull/127

### New Contributors

* @poldixd made their first contribution in https://github.com/spatie/laravel-honeypot/pull/127

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.5.0...4.5.1

## 4.5.0 - 2024-02-29

### What's Changed

* Laravel 11.x Support by @erikn69 in https://github.com/spatie/laravel-honeypot/pull/126

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.4.0...4.5.0

## 4.4.0 - 2023-12-01

### What's Changed

* GitHub Actions V4 by @erikn69 in https://github.com/spatie/laravel-honeypot/pull/123
* Add instructions on how to use with Volt functional syntax by @jcsoriano in https://github.com/spatie/laravel-honeypot/pull/125

### New Contributors

* @jcsoriano made their first contribution in https://github.com/spatie/laravel-honeypot/pull/125

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.3.4...4.4.0

## 4.3.4 - 2023-07-19

Fix `autocomplete` attribute value

## 4.3.3 - 2023-07-17

### What's Changed

- Fix badges by @erikn69 in https://github.com/spatie/laravel-honeypot/pull/116
- fix typo by @conlacda in https://github.com/spatie/laravel-honeypot/pull/118
- Fixes not dispatching SpamDetectedEvent in Livewire by @fsamapoor in https://github.com/spatie/laravel-honeypot/pull/120

### New Contributors

- @conlacda made their first contribution in https://github.com/spatie/laravel-honeypot/pull/118
- @fsamapoor made their first contribution in https://github.com/spatie/laravel-honeypot/pull/120

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.3.2...4.3.3

## 4.3.2 - 2023-01-17

### What's Changed

- PHP 8.2 Build by @erikn69 in https://github.com/spatie/laravel-honeypot/pull/112
- Refactor tests to Pest by @alexmanase in https://github.com/spatie/laravel-honeypot/pull/113
- Laravel 10.x Support by @erikn69 in https://github.com/spatie/laravel-honeypot/pull/115

### New Contributors

- @erikn69 made their first contribution in https://github.com/spatie/laravel-honeypot/pull/112
- @alexmanase made their first contribution in https://github.com/spatie/laravel-honeypot/pull/113

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.3.1...4.3.2

## 4.3.1 - 2022-08-03

### What's Changed

- handle DecryptException when valid_from field is a non-encrypted valu… by @mikemunger in https://github.com/spatie/laravel-honeypot/pull/110

### New Contributors

- @mikemunger made their first contribution in https://github.com/spatie/laravel-honeypot/pull/110

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.3.0...4.3.1

## 4.3.0 - 2022-05-23

## What's Changed

- SpamProtection to use the honeypot everywhere by @masterix21 in https://github.com/spatie/laravel-honeypot/pull/107

## New Contributors

- @masterix21 made their first contribution in https://github.com/spatie/laravel-honeypot/pull/107

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.2.3...4.3.0

## 4.2.3 - 2022-05-12

## What's Changed

- Don't specify return type for middleware (too many different types possible)
- Update .gitattributes by @angeljqv in https://github.com/spatie/laravel-honeypot/pull/102
- Update README.md - Added 'valid_from_timestamp' by @vdvcoder in https://github.com/spatie/laravel-honeypot/pull/105

## New Contributors

- @angeljqv made their first contribution in https://github.com/spatie/laravel-honeypot/pull/102
- @vdvcoder made their first contribution in https://github.com/spatie/laravel-honeypot/pull/105

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.2.2...4.2.3

## 4.2.2 - 2022-03-24

- Revert back to using inline styles (to avoid CSP issues)
- Add `aria-hidden` for screenreaders
- Use `autocomplete=nope` to disable autocomplete (https://stackoverflow.com/questions/12374442/chrome-ignores-autocomplete-off)

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.2.1...4.2.2

## 4.2.1 - 2022-03-23

- Add `autocomplete=off` to input fields

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.2.0...4.2.1

## 4.2.0 - 2022-03-23

## What's Changed

- Add `tabindex=-1` to fields
- Use custom class to hide honeypot fields
- Increase default honeypot timeout to 3 seconds
- Update .gitattributes by @PaolaRuby in https://github.com/spatie/laravel-honeypot/pull/100
- Fix typo in docs by @mertasan in https://github.com/spatie/laravel-honeypot/pull/101

## New Contributors

- @PaolaRuby made their first contribution in https://github.com/spatie/laravel-honeypot/pull/100
- @mertasan made their first contribution in https://github.com/spatie/laravel-honeypot/pull/101

**Full Changelog**: https://github.com/spatie/laravel-honeypot/compare/4.1.0...4.2.0

## 4.1.0 - 2022-01-13

- support Laravel 9

## 4.0.0 - 2021-04-13

- add support for Fortify
- drop support for PHP 7

## 3.0.1 - 2020-11-04

- add support for PHP 8

## 3.0.0 - 2020-09-16

- drop support for anything below PHP 7.4, Laravel 8
- add `x-honeypot` Blade component
- move setup to dedicated class, so it can be used in Inertia

## 2.3.0 - 2020-09-08

- add support for Laravel 8
- add option to skip `valid_from` timestamp check

## 2.2.0 - 2020-04-14

- reverts back `block all requests without honeypot fields` and adds an option to enable it

## 2.1.0 - 2020-03-02

- drop support for anything below Laravel 7

## 2.0.0 - 2020-03-02

- block all requests without honeypot fields

## 1.5.0 - 2020-03-02

- make compatible with Laravel 7

## 1.4.0 - 2019-04-09

- make compatible with Laravel 6

## 1.3.4 - 2019-06-12

- improve validation of the timestamp

## 1.3.3 - 2019-03-18

- fix invalid payload error

## 1.3.2 - 2019-03-06

- drop support for Laravel 5.7 and Carbon 1

## 1.3.1 - 2019-02-27

- fix requirements

## 1.3.0 - 2019-02-27

- drop support for PHP 7.1

## 1.2.0 - 2019-02-27

- add support for Laravel 5.8

## 1.1.3 - 2019-02-15

- fix name field name

## 1.1.2 - 2019-02-15

- restrict honeypot to POST requests only

## 1.1.1 - 2019-02-15

- use underscores instead of dashes for form field names

## 1.1.0 - 2018-01-03

- add randomized name field name

## 1.0.2 - 2018-12-27

- fix view hint path

## 1.0.1 - 2018-12-27

- allow Laravel 5.6

## 1.0.0 - 2018-12-27

- initial release

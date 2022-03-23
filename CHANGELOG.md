# Changelog

All notable changes to `laravel-honeypot` will be documented in this file

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

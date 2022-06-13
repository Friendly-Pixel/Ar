# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.2.0] - 2022-06-13
### Changed
- Fix php 8.1 deprecation messages.

## [2.0.0] - 2022-05-10
### Changed
- Use generic PHPdoc everywhere using `@Template`. This should allow for correct type interference in static analysers like PHPStan, Psalm.

## [1.0.0] - 2022-01-18
### Changed
- Changed behaviour of `Ar::filter` and `Ar::uniqueValues` to automatically preserve keys only when logical based on the new `array_is_list` function. A polyfill for this function is loaded for lower PHP versions.
### Removed
- Removed `Ar::filterValues` use `Ar::filter`
- Removed `Ar::uniqueValues` use `Ar::unique`
- Removed `Ar::new` use `Ar::wrap`
- Removed PHP 7.2 support

## [0.14.0] - 2021-03-25
- Use `iterable` typehint everywhere
- Add `Ar::merge`.

## [0.13.0] - 2021-01-11
- Add `Ar::unique`.
- Add `Ar::uniqueValues`.

## [0.12.0] - 2020-09-16
- Require PHP version 7.2 minimum
- Add `Ar::first`.
- Add `Ar::last`.
- Add `Ar::unshift`.
- Add `Ar::push`.

## [0.11.0] - 2020-04-21
- Add `Ar::unique`.
- Add `Ar::wrap` as the preferred syntax instead of `Ar::new`, as it's nice and symmetric with `->unwrap()`.

## [0.9.0] - 2020-04-21
### Changed
- Throw ArgumentException when not passing in an array or iterable.
- Add `implode`
- Add `filterValues`
- Add `keys`
- Add `values`
### Added
- 100% code coverage in tests

## [0.3.0] - 2019-08-27
### Changed
- Allow iterable everywhere.
- Make ArFluent immutable.
### Added
- Improve tests.

## [0.3.0] - 2019-08-27
### Changed
- Allow iterable everywhere.
- Make ArFluent immutable.
### Added
- Improve tests.

## [0.0.1] - 2019-04-27
### Added
- Initial release

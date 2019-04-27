# Ar - PHP array utility functions

<!-- [![Build Status](https://travis-ci.org/adbario/php-dot-notation.svg?branch=2.x)](https://travis-ci.org/adbario/php-dot-notation)
[![Coverage Status](https://coveralls.io/repos/github/adbario/php-dot-notation/badge.svg?branch=2.x)](https://coveralls.io/github/adbario/php-dot-notation?branch=2.x)
[![Total Downloads](https://poser.pugx.org/adbario/php-dot-notation/downloads)](https://packagist.org/packages/adbario/php-dot-notation)
[![License](https://poser.pugx.org/adbario/php-dot-notation/license)](LICENSE.md) -->

Consistent and (optionally) fluent `map`, `reduce` etc. for PHP arrays.

* All functional style functions accept the array as first parameter.
* All user-supplied callables get `$value, $key` as parameters.

Functional style:

```php
use Frontwise\Ar;

$ints = [1, 5, 8];
$ints = Ar::map($ints, function ($int) { return $int * $int; });
$ints = Ar::filter($ints, function ($int) { return $int % 2 == 0; })
```

Fluent style:

```php
$ints = ar([1, 5, 8])
    ->map(function ($int, $key) { return $int * $int; })
    ->filter(function ($int) { return $int % 2 == 0; })
;
```

## Install

Install the latest version using [Composer](https://getcomposer.org/):

```
$ composer require frontwise/ar
```

## Methods

Ar has the following methods:

- [filter()](#filter)
- [map()](#map)
- [mapKeys()](#mapKeys)
- [Ar::unwrap()](#unwrap)

<a name="filter"></a>
### filter

Only return items that match.

Pass every item into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
Keys are preserved.

```php
use Frontwise\Ar;
// Functional
$even = Ar::filter([1, 2, 3], function($value, $key) { return $value % 2 == 0; }); 
// Result: [0 => 2, 2 => 2, 4 => 3]
```

```php
// Fluent
$even = ar([1, 2, 3])
    ->filter(function($value, $key) { return $value % 2 == 0; })
    ->unwrap()
;
```

<a name="map"></a>
### map

Transform values.

Pass every item into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar;
// Functional
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $number * 2; }); 
// Result: [2, 4, 6]
```

```php
// Fluent
$numbers = ar([1, 2, 3])
    ->map(function ($int, $key) { return $int * $int; })
    ->unwrap()
;
```

<a name="mapKeys"></a>
### mapKeys

Transform keys.

Pass every item and key into a user-supplied callable, and use the returned value as key in the result array.

```php
use Frontwise\Ar;
// Functional
$numbers = Ar::mapKeys([1, 2, 3], function($value, $key) { return $key * 2; }); 
// Result: [0 => 2, 2 => 2, 4 => 3]
```

```php
// Fluent
$numbers = ar([1, 2, 3])
    ->mapKeys(function($value, $key) { return $key * 2; })
    ->unwrap()
;
```

## License

[MIT license](LICENSE.md)
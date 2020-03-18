# Ar - PHP array utility functions

<!-- [![Build Status](https://travis-ci.org/adbario/php-dot-notation.svg?branch=2.x)](https://travis-ci.org/adbario/php-dot-notation)
[![Coverage Status](https://coveralls.io/repos/github/adbario/php-dot-notation/badge.svg?branch=2.x)](https://coveralls.io/github/adbario/php-dot-notation?branch=2.x)
[![Total Downloads](https://poser.pugx.org/adbario/php-dot-notation/downloads)](https://packagist.org/packages/adbario/php-dot-notation)
[![License](https://poser.pugx.org/adbario/php-dot-notation/license)](LICENSE.md) -->

Consistent and (optionally) fluent `map`, `reduce` etc. for PHP arrays.

* All functional style functions accept the array as first parameter.
* Immutable: the input array is never modified. Fluent style returns a new object for every call.

Functional style:

```php
use Frontwise\Ar\Ar;

$ints = [1, 5, 8];
$ints = Ar::map($ints, function($value, $key) { return $value * $value; });
$ints = Ar::filter($ints, function($value, $key) { return $value % 2 == 0; })
```

Fluent style:

```php
use Frontwise\Ar\Ar;

$ints = Ar::new([1, 5, 8])
    ->map(function($value, $key) { return $value * $value; })
    ->filter(function($value, $key) { return $value % 2 == 0; })
    ->unwrap()
;
```

## Install

Install the latest version using [Composer](https://getcomposer.org/):

```
$ composer require frontwise/ar
```

## Methods

- [filter()](#filter)
- [map()](#map)
- [mapKeys()](#mapKeys)
- [search()](#search)

Fluent style only:

- [new()](#new)
- [implode()](#implode)
- [unwrap()](#unwrap)



<a name="filter"></a>
### filter

Only return items that match.

Pass every item into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
Keys are preserved.

```php
// Functional
use Frontwise\Ar\Ar;

$even = Ar::filter([1, 2, 3], function($value, $key) { return $value % 2 == 0; }); 

// Result: [0 => 2, 2 => 2, 4 => 3]
```

```php
// Fluent
use Frontwise\Ar\Ar;

$even = Ar::new([1, 2, 3])
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
// Functional
use Frontwise\Ar\Ar;

$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 

// Result: [2, 4, 6]
```

```php
// Fluent
use Frontwise\Ar\Ar;

$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
```



<a name="mapKeys"></a>
### mapKeys

Transform keys.

Pass every item and key into a user-supplied callable, and use the returned value as key in the result array.

```php
// Functional
use Frontwise\Ar\Ar;

$numbers = Ar::mapKeys([1, 2, 3], function($value, $key) { return $key * 2; }); 

// Result: [0 => 2, 2 => 2, 4 => 3]
```

```php
// Fluent
use Frontwise\Ar\Ar;

$numbers = Ar::new([1, 2, 3])
    ->mapKeys(function($value, $key) { return $key * 2; })
    ->unwrap()
;
```



<a name="search"></a>
### search

Return the first value for which the callable returns `true`.
Returns `null` otherwise.

```php
// Functional
use Frontwise\Ar\Ar;

$found = Ar::search([ ['a' => 1], ['a' => 8], ['a' => 3] ], function($value, $key) { return $value['a'] == 3; }); 

// Result: ['a' => 3]
```

```php
// Fluent
use Frontwise\Ar\Ar;

$found = Ar::new([ ['a' => 1], [], ['a' => 3] ])
    ->search(function($value, $key) { return $value['a'] == 3; })
;
```



## Fluent style only methods




<a name="new"></a>
### new

(When using fluent style): Create a new ArFluent object wrapping the array.

```php
// Fluent
use Frontwise\Ar\Ar;

$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
;

// If you don't like the Ar::new syntax, you can also use ArFluent directly:
use Frontwise\Ar\ArFluent;

$numbers = (new ArFluent([1, 2, 3]))
    ->map(function ($value, $key) { return $value * 2; })
;

```



<a name="unwrap"></a>
### unwrap

(When using fluent style): get the underlying array back.

```php
// Fluent
use Frontwise\Ar\Ar;

$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```




<a name="implode"></a>
### implode

(When using fluent style): Join all items into a big string, using `$glue` as separator.

```php
// Fluent
use Frontwise\Ar\Ar;

$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->join(' - ')
;
// Result: "1 - 2 - 3"
```



## License

[MIT license](LICENSE)

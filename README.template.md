# Ar makes working with PHP arrays easy

Fluent style:

```php
use FriendlyPixel\Ar\Ar;

$ints = Ar::wrap([1, 6, 8])
    ->map(fn ($num) => $num * $num)
    ->filterValues(fn ($value, $key) => $value % 2 == 0)
    ->unwrap();

// Result: [36, 64]
```

Functional style:

```php
use FriendlyPixel\Ar\Ar;

$ints = [1, 5, 8];
$ints = Ar::map($ints, fn($num) => $num * $num);
$ints = Ar::filter($ints, fn($value, $key) => $value % 2 == 0)
```

* Consistent: All functional style functions accept the array as first parameter.
* Immutable: the input array is never modified. Fluent style returns a new object for every call.
* Tested: unit-tested with 100% code coverage.
* Familiar: function names follow PHP whereever possible.


![](https://github.com/Friendly-Pixel/Ar/workflows/PHPUnit%20tests/badge.svg)

## Install

Install the latest version using [Composer](https://getcomposer.org/):

```
$ composer require friendly-pixel/ar
```

## Methods

<!-- METHOD_TOC_HERE -->

Fluent style only:

- [wrap()](#wrap)
- [unwrap()](#unwrap)


<!-- METHODS_HERE -->







## Fluent style only methods




<a name="wrap"></a>
### wrap

Wrap an array, so you can use fluent syntax to call multiple methods on it.
Use `->unwrap()` at the end if you need a pure array again.

```php
use FriendlyPixel\Ar\Ar;
$numbers = Ar::wrap([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->filter(function ($value) { return $value != 6; })
    ->unwrap()
;

// If you don't like the Ar::wrap syntax, you can also use ArFluent directly:
use FriendlyPixel\Ar\ArFluent;

$numbers = (new ArFluent([1, 2, 3]))
    ->map(function ($value, $key) { return $value * 2; })
    ->filter(function ($value) { return $value != 6; })
    ->unwrap()
;

```



<a name="unwrap"></a>
### unwrap

Return the underlying array.

```php
use FriendlyPixel\Ar\Ar;
$numbers = Ar::wrap([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```



<a name="toArray"></a>
### toArray

Alias for [unwrap()](#unwrap)




## License

[MIT license](LICENSE)

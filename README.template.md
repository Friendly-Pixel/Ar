


<!-- !!!!!!!! Never modify README.md! Only modify README_template.md then run `copy_docs.php` !!!!!!!!!!!!! -->



# Ar - PHP array utility functions

Consistent and (optionally) fluent `map`, `reduce` etc. for PHP arrays.

* All functional style functions accept the array as first parameter.
* Immutable: the input array is never modified. Fluent style returns a new object for every call.
* Tested: unit-tested with 100% code coverage.

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

<!-- METHOD_TOC_HERE -->

Fluent style only:

- [new()](#new)
- [unwrap()](#unwrap)
- [toArray()](#toArray)



<!-- METHODS_HERE -->







## Fluent style only methods




<a name="new"></a>
### new

Create a new ArFluent object wrapping the array.

```php
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

Return the underlying array.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::new([1, 2, 3])
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

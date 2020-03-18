


<!-- !!!!!!!! Never modify README.md! Only modify README_template.md then run `copy_docs.php` !!!!!!!!!!!!! -->



# Ar - PHP array utility functions

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
- [flat()](#flat)
- [forEach()](#forEach)
- [map()](#map)
- [mapKeys()](#mapKeys)
- [reduce()](#reduce)
- [search()](#search)
- [sort()](#sort)

Fluent style only:

- [new()](#new)
- [implode()](#implode)
- [unwrap()](#unwrap)
- [toArray()](#toArray)



<a name="filter"></a>
### filter

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```

@param callable $callable function ($value, $key): mixed
@return mixed[]



<a name="flat"></a>
### flat

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```

@param callable $callable function ($value, $key): mixed
@return mixed[]



<a name="forEach"></a>
### forEach

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```

@param callable $callable function ($value, $key): mixed
@return mixed[]



<a name="map"></a>
### map

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```

@param callable $callable function ($value, $key): mixed
@return mixed[]



<a name="mapKeys"></a>
### mapKeys

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```

@param callable $callable function ($value, $key): mixed
@return mixed[]



<a name="reduce"></a>
### reduce

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```

@param callable $callable function ($value, $key): mixed
@return mixed[]



<a name="search"></a>
### search

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```

@param callable $callable function ($value, $key): mixed
@return mixed[]



<a name="sort"></a>
### sort

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```

@param callable $callable function ($value, $key): mixed
@return mixed[]










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



<a name="implode"></a>
### implode

Join all values into a big string, using `$glue` as separator.
`$glue` is optional.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::new([1, 2, 3])
    ->map(function ($value, $key) { return $value * 2; })
    ->join(' - ')
;
// Result: "1 - 2 - 3"
```



## License

[MIT license](LICENSE)

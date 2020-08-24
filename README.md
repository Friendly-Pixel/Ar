


<!-- !!!!!!!! Never modify README.md! Only modify README_template.md then run `copy_docs.php` !!!!!!!!!!!!! -->



# Ar - PHP array utility functions

Consistent and (optionally) fluent `map`, `reduce` etc. for PHP arrays.

* All functional style functions accept the array as first parameter.
* Immutable: the input array is never modified. Fluent style returns a new object for every call.
* Tested: unit-tested with 100% code coverage.

Functional style:

```php
use FriendlyPixel\Ar\Ar;
$ints = [1, 5, 8];
$ints = Ar::map($ints, function($value, $key) { return $value * $value; });
$ints = Ar::filter($ints, function($value, $key) { return $value % 2 == 0; })
```

Fluent style:

```php
use FriendlyPixel\Ar\Ar;
$ints = Ar::wrap([1, 5, 8])
    ->map(function($value, $key) { return $value * $value; })
    ->filter(function($value, $key) { return $value % 2 == 0; })
    ->unwrap()
;
```

![](https://github.com/Friendly-Pixel/Ar/workflows/PHPUnit%20tests/badge.svg)

## Install

Install the latest version using [Composer](https://getcomposer.org/):

```
$ composer require friendly-pixel/ar
```

## Methods

- [count()](#count)
- [filter()](#filter)
- [filterValues()](#filterValues)
- [flat()](#flat)
- [forEach()](#forEach)
- [implode()](#implode)
- [keys()](#keys)
- [map()](#map)
- [mapKeys()](#mapKeys)
- [reduce()](#reduce)
- [search()](#search)
- [sort()](#sort)
- [values()](#values)

Fluent style only:

- [wrap()](#wrap)
- [unwrap()](#unwrap)


<a name="count"></a>
### count

Count how many items there are in the array.

```php
use FriendlyPixel\Ar\Ar;
$count = Ar::count([1, 2, 3]); 
$count = Ar::wrap([1, 2, 3])
    ->count()
;
// Result: 3
```



<a name="filter"></a>
### filter

Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
Keys are preserved, this means that the returned array can have "gaps" in the keys. Use `filterValues` if you want a sequential result.

```php
use FriendlyPixel\Ar\Ar;
$even = Ar::filter([1, 2, 3, 12], function($value, $key) { return $value % 2 == 0; }); 
$even = Ar::wrap([1, 2, 3, 12])
    ->filter(function($value, $key) { return $value % 2 == 0; })
    ->unwrap()
;
// Result: [1 => 2, 3 => 12]
```


@param callable $callable ($value, $key): bool

@return mixed[]



<a name="filterValues"></a>
### filterValues

Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
Keys are not preserved, the returned array is sequential. Use `filter` to preserve keys.

```php
use FriendlyPixel\Ar\Ar;
$even = Ar::filter([1, 2, 3, 12], function($value, $key) { return $value % 2 == 0; }); 
$even = Ar::wrap([1, 2, 3, 12])
    ->filter(function($value, $key) { return $value % 2 == 0; })
    ->unwrap()
;
// Result: [2, 12]
```


@param callable $callable ($value, $key): bool

@return mixed[]



<a name="flat"></a>
### flat

The flat() method creates a new array with all sub-array elements concatenated into it recursively up to the specified depth.

@param int $depth To what level to flatten the array. Default: 1

@return mixed[]



<a name="forEach"></a>
### forEach

Walk over every value, key.
Pass every value, key into a user-supplied callable.


@param callable $callable ($value, $key)

@return mixed[]



<a name="implode"></a>
### implode

Join all values into a big string, using `$glue` as separator.
`$glue` is optional.

```php
use FriendlyPixel\Ar\Ar;
$result = Ar::implode(['a', 'b', 'c'], ','); 
$result = Ar::wrap(['a', 'b', 'c'])
    ->implode(',')
;
// result: "a,b,c"
```



<a name="keys"></a>
### keys

Return the keys of an array as a sequential array.

```php
use FriendlyPixel\Ar\Ar;
$result = Ar::keys([3 => 'a', 'foo' => 'b', 1 => 'c']); 
$result = Ar::wrap([3 => 'a', 'foo' => 'b', 1 => 'c'])->keys()->unwrap();
// result: [3, 'foo', 1]
```


@return mixed[]



<a name="map"></a>
### map

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use FriendlyPixel\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::wrap([1, 2, 3])
    ->map(function($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```


@param callable $callable ($value, $key): mixed

@return mixed[]



<a name="mapKeys"></a>
### mapKeys

Transform keys.
Pass every value, key and key into a user-supplied callable, and use the returned value as key in the result array.

```php
use FriendlyPixel\Ar\Ar;
$numbers = Ar::mapKeys([1, 2, 3], function($value, $key) { return $key * 2; }); 
$numbers = Ar::wrap([1, 2, 3])
    ->mapKeys(function($value, $key) { return $key * 2; })
    ->unwrap()
;
// Result: [0 => 2, 2 => 2, 4 => 3]
```


@param callable $callable ($value, $key): mixed

@return mixed[]



<a name="reduce"></a>
### reduce

Iteratively reduce the array to a single value using a callback function.


@param mixed|null $initial If the optional initial is available, it will be used at the beginning of the process, or as a final result in case the array is empty.

@param callable $callable function($carry, $value, $key): mixed

@return mixed



<a name="search"></a>
### search

Return the first value for which the callable returns `true`.
Returns `null` otherwise.

```php
use FriendlyPixel\Ar\Ar;
$found = Ar::search([ ['a' => 1], ['a' => 8], ['a' => 3] ], function($value, $key) { return $value['a'] == 3; }); 
$found = Ar::wrap([ ['a' => 1], [], ['a' => 3] ])
    ->search(function($value, $key) { return $value['a'] == 3; })
;
// Result: ['a' => 3]
```


@param callable $callable ($value, $key): bool

@return mixed



<a name="sort"></a>
### sort

Sort an array by values using a user-defined comparison function.


@param callable $callable    function($valueA, $valueB): int 
                             Return an integer smaller then, equal to,
                             or larger than 0 to indicate that $valueA is less
                             then, equal to, or larger than $valueB.

@return mixed[]



<a name="values"></a>
### values

Return the values of an array as a sequential array.

```php
use FriendlyPixel\Ar\Ar;
$result = Ar::values([3 => 'a', 'foo' => 'b', 1 => 'c']); 
$result = Ar::wrap([3 => 'a', 'foo' => 'b', 1 => 'c'])->values()->unwrap();
// result: [0 => 'a', 1 => 'b', 2 => 'c']
```


@return mixed[]










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




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
- [implode()](#implode)
- [map()](#map)
- [mapKeys()](#mapKeys)
- [reduce()](#reduce)
- [search()](#search)
- [sort()](#sort)

Fluent style only:

- [new()](#new)
- [unwrap()](#unwrap)
- [toArray()](#toArray)



<a name="filter"></a>
### filter

Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$even = Ar::filter([1, 2, 3], function($value, $key) { return $value % 2 == 0; }); 
$even = Ar::new([1, 2, 3])
    ->filter(function($value, $key) { return $value % 2 == 0; })
    ->unwrap()
;
// Result: [0 => 2, 2 => 2, 4 => 3]
```


@param callable $callable callable($value, $key): bool

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


@param callable $callable callable($value, $key)

@return mixed[]



<a name="implode"></a>
### implode

Join all values into a big string, using `$glue` as separator.
`$glue` is optional.

```php
use Frontwise\Ar\Ar;
$result = Ar::implode(['a', 'b', 'c'], ','); 
$result = Ar::new(['a', 'b', 'c'])
    ->implode(',')
;
// result: "a,b,c"
```



<a name="map"></a>
### map

Transform values.
Pass every value, key into a user-supplied callable, and put the returned value into the result array.
Keys are preserved.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->map(function($value, $key) { return $value * 2; })
    ->unwrap()
;
// Result: [2, 4, 6]
```


@param callable $callable callable($value, $key): mixed

@return mixed[]



<a name="mapKeys"></a>
### mapKeys

Transform keys.
Pass every value, key and key into a user-supplied callable, and use the returned value as key in the result array.

```php
use Frontwise\Ar\Ar;
$numbers = Ar::mapKeys([1, 2, 3], function($value, $key) { return $key * 2; }); 
$numbers = Ar::new([1, 2, 3])
    ->mapKeys(function($value, $key) { return $key * 2; })
    ->unwrap()
;
// Result: [0 => 2, 2 => 2, 4 => 3]
```


@param callable $callable callable($value, $key): mixed

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
use Frontwise\Ar\Ar;
$found = Ar::search([ ['a' => 1], ['a' => 8], ['a' => 3] ], function($value, $key) { return $value['a'] == 3; }); 
$found = Ar::new([ ['a' => 1], [], ['a' => 3] ])
    ->search(function($value, $key) { return $value['a'] == 3; })
;
// Result: ['a' => 3]
```


@param callable $callable callable($value, $key): bool

@return mixed



<a name="sort"></a>
### sort

Sort an array by values using a user-defined comparison function.


@param callable $callable    function($valueA, $valueB): int 
                             Return an integer smaller then, equal to,
                             or larger than 0 to indicate that $valueA is less
                             then, equal to, or larger than $valueB.

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




## License

[MIT license](LICENSE)

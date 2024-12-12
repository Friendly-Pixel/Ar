<!-- 
!!!!!!!!
!!!!!!!!
!!!!!!!!
!!!!!!!!
!!!!!!!!
!!!!!!!!
!!!!!!!!
!!!!!!!! Never modify this file, `README.md`
!!!!!!!!
!!!!!!!! Modify `README_template.md` instead then run `copy_docs.php`
!!!!!!!!
!!!!!!!!
!!!!!!!!
!!!!!!!!
!!!!!!!!
!!!!!!!!
!!!!!!!!
-->
# Ar makes working with PHP arrays easy

* __Consistent__: All functions accept the array as first parameter.
* __Immutable__: the input array is never modified. Fluent style returns a new object for every call.
* __Tested__: unit-tested with 100% code coverage. ![](https://github.com/Friendly-Pixel/Ar/workflows/PHPUnit%20tests/badge.svg)
* __Familiar__: function names follow PHP whereever possible.

Fluent style:

```php
use FriendlyPixel\Ar\Ar;

$ints = Ar::wrap([1, 6, 8])
    ->map(fn ($num) => $num * $num)
    ->filter(fn ($value, $key) => $value % 2 == 0);
```

Functional style:

```php
use FriendlyPixel\Ar\Ar;

$ints = [1, 5, 8];
$ints = Ar::map($ints, fn($num) => $num * $num);
$ints = Ar::filter($ints, fn($value, $key) => $value % 2 == 0)
```

## Install

Install the latest version using [Composer](https://getcomposer.org/):

```
$ composer require friendly-pixel/ar
```

## Methods

- [count()](#count)
- [filter()](#filter)
- [first()](#first)
- [flat()](#flat)
- [forEach()](#forEach)
- [implode()](#implode)
- [keys()](#keys)
- [last()](#last)
- [map()](#map)
- [mapKeys()](#mapKeys)
- [merge()](#merge)
- [push()](#push)
- [reduce()](#reduce)
- [search()](#search)
- [slice()](#slice)
- [sort()](#sort)
- [splice()](#splice)
- [unique()](#unique)
- [unshift()](#unshift)
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
Keys are preserved only when `array_is_list($array)` returns false;

```php
use FriendlyPixel\Ar\Ar;

$even = Ar::filter([1, 2, 3, 12], function($value, $key) { return $value % 2 == 0; }); 
$even = Ar::wrap([1, 2, 3, 12])
    ->filter(function($value, $key) { return $value % 2 == 0; })
    ->unwrap();
// Result: [1 => 2, 3 => 12]
```


@template A

@param A[] $array 

@param callable(A $value, mixed $key): bool $callable

@return A[]



<a name="first"></a>
### first

Returns the first value of the array or `false` when it's empty.

```php
use FriendlyPixel\Ar\Ar;

Ar::first([2, 3, 4]);
Ar::wrap([2, 3, 4])->first();

// Result: 2
```


@template A

@param A[] $array 

@return A



<a name="flat"></a>
### flat

The flat() method creates a new array with all sub-array elements concatenated into it recursively up to the specified depth.

@param int $depth To what level to flatten the array. Default: 1

@return mixed[]



<a name="forEach"></a>
### forEach

Walk over every value, key.
Pass every value, key into a user-supplied callable.


@template A

@param A[] $array 

@param callable(A $value, mixed $key): void $callable

@return A[] Original array, unmodified



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



<a name="last"></a>
### last

Returns the last value of the array or `false` when it's empty.

```php
use FriendlyPixel\Ar\Ar;

Ar::last([2, 3, 4]);
Ar::wrap([2, 3, 4])->last();

// Result: 4
```


@template A

@param A[] $array 

@return A



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
    ->unwrap();
// Result: [2, 4, 6]
```


@template A

@template B

@param A[] $array 

@param callable(A $value, mixed $key): B $callable

@return B[]



<a name="mapKeys"></a>
### mapKeys

Transform keys.
Pass every value, key and key into a user-supplied callable, and use the returned value as key in the result array.

```php
use FriendlyPixel\Ar\Ar;

$numbers = Ar::mapKeys([1, 2, 3], function($value, $key) { return $key * 2; }); 
$numbers = Ar::wrap([1, 2, 3])
    ->mapKeys(function($value, $key) { return $key * 2; })
    ->unwrap();
// Result: [0 => 2, 2 => 2, 4 => 3]
```


@template A

@template K

@param A[] $array 

@param callable(A $value, mixed $key): K $callable

@return array<K, A>



<a name="merge"></a>
### merge

Merges the elements of one or more arrays together so that the values of one are appended to the end of the previous one.
If the input arrays have the same string keys, then the later value for that key will overwrite the previous one. If, however, the arrays contain numeric keys, the later value will not overwrite the original value, but will be appended.
Values in the input arrays with numeric keys will be renumbered with incrementing keys starting from zero in the result array.

```php
use FriendlyPixel\Ar\Ar;

$numbers = Ar::merge(['a', 'b'], ['c', 'd'])); 
$numbers = Ar::wrap(['a', 'b'])
    ->merge(['b', 'c'])
    ->unwrap();
// Result:['a', 'b', 'c', 'd']
```


@template A

@var A[][] $arrays

@return A[]



<a name="push"></a>
### push

Append one or more items to the end of array.

```php
use FriendlyPixel\Ar\Ar;

$result = Ar::push([1, 2], 3, 4); 
$result = Ar::wrap([1, 2])->push(3, 4)->unwrap();

// result: [1, 2, 3, 4]
```


@template A

@param A[] $array 

@param A[] $values

@return A[]



<a name="reduce"></a>
### reduce

Iteratively reduce the array to a single value using a callback function.


@template A

@template B

@param A[] $array 

@param callable(B|null $carry, A $value, mixed $key): B $callable

@param B|null $initial If the optional initial is available, it will be used at the beginning of the process, or as a final result in case the array is empty.

@return B



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


@template A

@param A[] $array 

@param callable(A $value, mixed $key): bool $callable

@return A|null



<a name="slice"></a>
### slice

Extract a slice of the array, include `$length` items, and starting from `$offset`.

```php
use FriendlyPixel\Ar\Ar;

$even = Ar::slice(['a', 'b', 'c', 'd'], 1, 2); 
$even = Ar::wrap(['a', 'b', 'c', 'd'])
    ->slice(1, 2)
    ->unwrap();
// Result: ['b', 'c']
```


@template A

@param A[] $array 

@param int $offset
     If offset is non-negative, the sequence will start at that offset in the array.
     If offset is negative, the sequence will start that far from the end of the array. 

@param ?int $length 
     If length is given and is positive, then the sequence will have up to that many elements
     in it.
     If the array is shorter than the length, then only the available array elements will be
     present.
     If length is given and is negative then the sequence will stop that many elements from 
     the end of the array.
     If it is omitted, then the sequence will have everything from offset up until the end of
     the array.

@return A[]



<a name="sort"></a>
### sort

Sort an array by values using a user-defined comparison function.

This function assigns new keys to the elements in array. It will remove any existing keys that may have been assigned.



@template A

@param A[] $array 

@param callable(A $valueA, A $valueB): int $callable    
                             Return an integer smaller then, equal to,
                             or larger than 0 to indicate that $valueA is less
                             then, equal to, or larger than $valueB.

@return A[]



<a name="splice"></a>
### splice

Remove a portion of the array and replace it with something else.
Other than the default php function, this returns the changed array, not the extracted
elements.

```php
use FriendlyPixel\Ar\Ar;

$even = Ar::splice(['a', 'b', 'c', 'd'], 1, 1, ['q', 'x']); 
$even = Ar::wrap(['a', 'b', 'c', 'd'])
    ->splice(1, 1, ['q', 'x'])
    ->unwrap();
// Result: ['a', 'q', 'x', 'c', 'd']
```


@template A

@param A[] $array 

@param int $offset
     If offset is positive then the start of the removed portion is at that offset from 
     the beginning of the array. 

     If offset is negative then the start of the removed portion is at that offset from 
     the end of the array. 

@param ?int $length 
     If length is omitted, removes everything from offset to the end of the array.
     *
     If length is specified and is positive, then that many elements will be removed.
      
     If length is specified and is negative, then the end of the removed portion will be 
     that many elements from the end of the array.
      
     If length is specified and is zero, no elements will be removed. 

@param A[] $replacement
     If replacement array is specified, then the removed elements are replaced with elements 
     from this array.

     If offset and length are such that nothing is removed, then the elements from the 
     replacement array are inserted in the place specified by the offset. 
     *
     If replacement is just one element it is not necessary to put array() or square brackets 
     around it, unless the element is an array itself, an object or null. 
     
     Note: Keys in the replacement array are not preserved.

@return A[] Other than the default php function, this returns the changed array, not the 
    extracted elements.



<a name="unique"></a>
### unique

Remove duplicate values from array.
Keys are preserved only when `array_is_list($array)` returns false;

```php
use FriendlyPixel\Ar\Ar;

$result = Ar::unique(['a', 'a', 'b']); 
$result = Ar::wrap(['b', 4])->unique(['a', 'a', 'b'])->unwrap();

// result: [0 => 'a', 2 => 'b']
```


@template A

@param A[] $array 

@param int $flags The optional second parameter flags may be used to modify the sorting behavior using these values:
    Sorting type flags:
    
    SORT_REGULAR - compare items normally (don't change types)
    SORT_NUMERIC - compare items numerically
    SORT_STRING - compare items as strings
    SORT_LOCALE_STRING - compare items as strings, based on the current locale.

@return A[]



<a name="unshift"></a>
### unshift

Prepend one or more items to the beginning of array.

```php
use FriendlyPixel\Ar\Ar;

$result = Ar::unshift([3, 4], 1, 2); 
$result = Ar::wrap([3, 4])->unshift(1, 2)->unwrap();

// result: [1, 2, 3, 4]
```


@template A

@param A[] $array 

@param A[] $values 

@return A[]



<a name="values"></a>
### values

Return the values of an array as a sequential array.

```php
use FriendlyPixel\Ar\Ar;

$result = Ar::values([3 => 'a', 'foo' => 'b', 1 => 'c']); 
$result = Ar::wrap([3 => 'a', 'foo' => 'b', 1 => 'c'])->values()->unwrap();
// result: [0 => 'a', 1 => 'b', 2 => 'c']
```


@template A

@param array<mixed, A> $array 

@return array<int, A>










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

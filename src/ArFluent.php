<?php

namespace FriendlyPixel\Ar;

use ArrayAccess;
use IteratorAggregate;
use JsonSerializable;

class ArFluent implements IteratorAggregate, ArrayAccess, JsonSerializable
{
    /** @var array $array */
    private $array = [];

    public function __construct(iterable $array = null)
    {
        if ($array) {
            $this->array = Ar::makeArray($array);
        }
    }

    /* ======= */

    /**
     * Count how many items there are in the array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $count = Ar::count([1, 2, 3]); 
     * $count = Ar::wrap([1, 2, 3])
     *     ->count()
     * ;
     * // Result: 3
     * ```
     */
    public function count(): int
    {
        return Ar::count($this->array);
    }

    /**
     * Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
     * Keys are preserved, this means that the returned array can have "gaps" in the keys. Use `filterValues` if you want a sequential result.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $even = Ar::filter([1, 2, 3, 12], function($value, $key) { return $value % 2 == 0; }); 
     * $even = Ar::wrap([1, 2, 3, 12])
     *     ->filter(function($value, $key) { return $value % 2 == 0; })
     *     ->unwrap();
     * // Result: [1 => 2, 3 => 12]
     * ```
     * 
     * @param callable $callable ($value, $key): bool
     * @return ArFluent
     */
    public function filter(callable $callable): self
    {
        return new static(Ar::filter($this->array, $callable));
    }

    /**
     * Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
     * Keys are not preserved, the returned array is sequential. Use `filter` to preserve keys.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $even = Ar::filter([1, 2, 3, 12], function($value, $key) { return $value % 2 == 0; }); 
     * $even = Ar::wrap([1, 2, 3, 12])
     *     ->filter(function($value, $key) { return $value % 2 == 0; })
     *     ->unwrap();
     * // Result: [2, 12]
     * ```
     * 
     * @param callable $callable ($value, $key): bool
     * @return ArFluent
     */
    public function filterValues(callable $callable): self
    {
        return new static(Ar::filterValues($this->array, $callable));
    }

    /**
     * Returns the first value of the array or `false` when it's empty.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * Ar::first([2, 3, 4]);
     * Ar::wrap([2, 3, 4])->first();
     * 
     * // Result: 2
     * ```
     * 
     * @return mixed|false
     */
    public function first()
    {
        return Ar::first($this->array);
    }

    /**
     * The flat() method creates a new array with all sub-array elements concatenated into it recursively up to the specified depth.
     * @param int $depth To what level to flatten the array. Default: 1
     * @return ArFluent
     */
    public function flat($depth = 1)
    {
        return new static(Ar::flat($this->array, $depth));
    }

    /**
     * Walk over every value, key.
     * Pass every value, key into a user-supplied callable.
     * 
     * @param callable $callable ($value, $key)
     * @return ArFluent
     */
    public function forEach(callable $callable): self
    {
        Ar::forEach($this->array, $callable);
        return $this;
    }

    /**
     * Join all values into a big string, using `$glue` as separator.
     * `$glue` is optional.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $result = Ar::implode(['a', 'b', 'c'], ','); 
     * $result = Ar::wrap(['a', 'b', 'c'])
     *     ->implode(',')
     * ;
     * // result: "a,b,c"
     * ```
     */
    public function implode(string $glue = ''): string
    {
        return Ar::implode($this->array, $glue);
    }

    /**
     * Return the keys of an array as a sequential array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $result = Ar::keys([3 => 'a', 'foo' => 'b', 1 => 'c']); 
     * $result = Ar::wrap([3 => 'a', 'foo' => 'b', 1 => 'c'])->keys()->unwrap();
     * // result: [3, 'foo', 1]
     * ```
     * 
     * @return ArFluent
     */
    public function keys(): self
    {
        return new static(Ar::keys($this->array));
    }

    /**
     * Returns the last value of the array or `false` when it's empty.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * Ar::last([2, 3, 4]);
     * Ar::wrap([2, 3, 4])->last();
     * 
     * // Result: 4
     * ```
     * 
     * @return mixed|false
     */
    public function last()
    {
        return Ar::last($this->array);
    }

    /**
     * Transform values.
     * Pass every value, key into a user-supplied callable, and put the returned value into the result array.
     * Keys are preserved.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
     * $numbers = Ar::wrap([1, 2, 3])
     *     ->map(function($value, $key) { return $value * 2; })
     *     ->unwrap();
     * // Result: [2, 4, 6]
     * ```
     * 
     * @param callable $callable ($value, $key): mixed
     * @return ArFluent
     */
    public function map(callable $callable): self
    {
        return new static(Ar::map($this->array, $callable));
    }

    /**
     * Transform keys.
     * Pass every value, key and key into a user-supplied callable, and use the returned value as key in the result array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $numbers = Ar::mapKeys([1, 2, 3], function($value, $key) { return $key * 2; }); 
     * $numbers = Ar::wrap([1, 2, 3])
     *     ->mapKeys(function($value, $key) { return $key * 2; })
     *     ->unwrap();
     * // Result: [0 => 2, 2 => 2, 4 => 3]
     * ```
     * 
     * @param callable $callable ($value, $key): mixed
     * @return ArFluent
     */
    public function mapKeys(callable $callable): self
    {
        return new static(Ar::mapKeys($this->array, $callable));
    }

    /**
     * Merges the elements of one or more arrays together so that the values of one are appended to the end of the previous one.
     * If the input arrays have the same string keys, then the later value for that key will overwrite the previous one. If, however, the arrays contain numeric keys, the later value will not overwrite the original value, but will be appended.
     * Values in the input arrays with numeric keys will be renumbered with incrementing keys starting from zero in the result array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $numbers = Ar::merge(['a', 'b'], ['c', 'd'])); 
     * $numbers = Ar::wrap(['a', 'b'])
     *     ->merge(['b', 'c'])
     *     ->unwrap();
     * // Result:['a', 'b', 'c', 'd']
     * ```
     * 
     * @var iterable[] $arrays
     * @return ArFluent
     */
    public function merge(...$arrays): self
    {
        return new static(Ar::merge($this->array, ...$arrays));
    }

    /**
     * Append one or more items to the end of array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $result = Ar::push([1, 2], 3, 4); 
     * $result = Ar::wrap([1, 2])->push(3, 4)->unwrap();
     * 
     * // result: [1, 2, 3, 4]
     * ```
     * 
     * @return ArFluent
     */
    public function push(...$values): self
    {
        return new static(Ar::push($this->array, ...$values));
    }

    /**
     * Return the first value for which the callable returns `true`.
     * Returns `null` otherwise.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $found = Ar::search([ ['a' => 1], ['a' => 8], ['a' => 3] ], function($value, $key) { return $value['a'] == 3; }); 
     * $found = Ar::wrap([ ['a' => 1], [], ['a' => 3] ])
     *     ->search(function($value, $key) { return $value['a'] == 3; })
     * ;
     * // Result: ['a' => 3]
     * ```
     * 
     * @param callable $callable ($value, $key): bool
     * @return mixed
     */
    public function search(callable $callable)
    {
        return Ar::search($this->array, $callable);
    }

    /**
     * Sort an array by values using a user-defined comparison function.
     * 
     * This function assigns new keys to the elements in array. It will remove any existing keys that may have been assigned.
     * 
     * @param callable $callable    function($valueA, $valueB): int 
     *                              Return an integer smaller then, equal to,
     *                              or larger than 0 to indicate that $valueA is less
     *                              then, equal to, or larger than $valueB.
     * @return ArFluent
     */
    public function sort(callable $callable): self
    {
        return new static(Ar::sort($this->array, $callable));
    }

    /**
     * Iteratively reduce the array to a single value using a callback function.
     * 
     * @param mixed|null $initial If the optional initial is available, it will be used at the beginning of the process, or as a final result in case the array is empty.
     * @param callable $callable function($carry, $value, $key): mixed
     * @return mixed
     */
    public function reduce(callable $callable, $initial = null)
    {
        return Ar::reduce($this->array, $callable, $initial);
    }

    /**
     * Remove duplicate values from array.
     * Keys are preserved, use `uniqueValues` for a sequential result.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $result = Ar::unique(['a', 'a', 'b']); 
     * $result = Ar::wrap(['b', 4])->unique(['a', 'a', 'b'])->unwrap();
     * 
     * // result: [0 => 'a', 2 => 'b']
     * ```
     * 
     * @return ArFluent
     */
    public function unique(): self
    {
        return new static(Ar::unique($this->array));
    }

    /**
     * Remove duplicate values from array.
     * Keys are not preserved, the returned array is sequential. Use `unique` to preserve keys.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $result = Ar::uniqueValues(['a', 'a', 'b']); 
     * $result = Ar::wrap(['b', 4])->uniqueValues(['a', 'a', 'b'])->unwrap();
     * 
     * // result: ['a', 'b']
     * ```
     * 
     * @return ArFluent
     */
    public function uniqueValues(): self
    {
        return new static(Ar::uniqueValues($this->array));
    }

    /**
     * Prepend one or more items to the beginning of array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $result = Ar::unshift([3, 4], 1, 2); 
     * $result = Ar::wrap([3, 4])->unshift(1, 2)->unwrap();
     * 
     * // result: [1, 2, 3, 4]
     * ```
     * 
     * @return ArFluent
     */
    public function unshift(...$values): self
    {
        return new static(Ar::unshift($this->array, ...$values));
    }

    /**
     * Return the values of an array as a sequential array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $result = Ar::values([3 => 'a', 'foo' => 'b', 1 => 'c']); 
     * $result = Ar::wrap([3 => 'a', 'foo' => 'b', 1 => 'c'])->values()->unwrap();
     * // result: [0 => 'a', 1 => 'b', 2 => 'c']
     * ```
     * 
     * @return ArFluent
     */
    public function values(): self
    {
        return new static(Ar::values($this->array));
    }

    /* ======= Fluent only ======= */

    /**
     * Return the underlying array.
     * Alias for `ArFluent::unwrap`
     * @deprecated since 0.11.0. Use `unwrap` instead.
     * @return array 
     */
    public function toArray()
    {
        return $this->unwrap();
    }

    /**
     * Return the underlying array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * $numbers = Ar::wrap([1, 2, 3])
     *     ->map(function ($value, $key) { return $value * 2; })
     *     ->unwrap()
     * ;
     * // Result: [2, 4, 6]
     * ```
     * 
     * Often you don't need to call this, since you can use use an `ArFluent` instance like an array:
     * 
     * ```php
     * $letters = Ar::wrap(['a', 'b', 'c']);
     * $a = $letters[0]; // works
     * foreach ($letters as $letter) { // also works
     *      print($letter);
     * }
     * ```
     * @return array 
     */
    public function unwrap()
    {
        return $this->array;
    }

    /* === IteratorAggregate implementation === */

    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }

    /* === Arrayaccess implementation === */

    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    /* === JsonSerializable implementation === */

    public function jsonSerialize()
    {
        return $this->array;
    }
}

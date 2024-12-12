<?php

namespace FriendlyPixel\Ar;

use InvalidArgumentException;

class Ar
{
    /**
     * Wrap an array, so you can use fluent syntax to call multiple methods on it.
     * Use `->unwrap()` at the end if you need a pure array again.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $numbers = Ar::wrap([1, 2, 3])
     *     ->map(function ($value, $key) { return $value * 2; })
     *     ->filter(function ($value) { return $value != 6; })
     *     ->unwrap();
     * ```
     * 
     * @template A
     * @param A[] $array 
     * @return ArFluent<A>
     */
    public static function wrap(iterable $array): ArFluent
    {
        return new ArFluent($array);
    }

    /* === Functions, sorted alphabetically === */

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
    public static function count(iterable $array): int
    {
        $array = self::makeArray($array);
        return count($array);
    }

    /**
     * Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
     * Keys are preserved only when `array_is_list($array)` returns false;
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
     * @template A
     * @param A[] $array 
     * @param callable(A $value, mixed $key): bool $callable
     * @return A[]
     */
    public static function filter(iterable $array, callable $callable): array
    {
        $array = self::makeArray($array);
        $result = [];

        if (array_is_list($array)) {
            foreach ($array as $key => $value) {
                if (call_user_func($callable, $value, $key) === true) {
                    $result[] = $value;
                }
            }
        } else {
            foreach ($array as $key => $value) {
                if (call_user_func($callable, $value, $key) === true) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
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
     * @template A
     * @param A[] $array 
     * @return A
     */
    public static function first(iterable $array)
    {
        $array = self::makeArray($array);
        return reset($array);
    }

    /**
     * The flat() method creates a new array with all sub-array elements concatenated into it recursively up to the specified depth.
     * @param int $depth To what level to flatten the array. Default: 1
     * @return mixed[]
     */
    public static function flat(iterable $array, int $depth = 1)
    {
        $result = [];

        self::_flat($result, $array, $depth);

        return $result;
    }

    private static function _flat(array &$result, $input, int $depth)
    {
        foreach ($input as $value) {
            if (is_iterable($value) && $depth > 0) {
                self::_flat($result, $value, $depth - 1);
            } else {
                $result[] = $value;
            }
        }
    }

    /**
     * Walk over every value, key.
     * Pass every value, key into a user-supplied callable.
     * 
     * @template A
     * @param A[] $array 
     * @param callable(A $value, mixed $key): void $callable
     * @return A[] Original array, unmodified
     */
    public static function forEach(iterable $array, callable $callable): array
    {
        foreach ($array as $key => $value) {
            call_user_func($callable, $value, $key);
        }

        return $array;
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
    public static function implode(iterable $array, string $glue = ''): string
    {
        $array = self::makeArray($array);
        return implode($glue, $array);
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
     * @return mixed[]
     */
    public static function keys(iterable $array): array
    {
        $array = self::makeArray($array);
        return array_keys($array);
    }

    public static function makeArray(iterable $array)
    {
        if (is_array($array)) {
            return $array;
        }

        return iterator_to_array($array, true);
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
     * @template A
     * @param A[] $array 
     * @return A
     */
    public static function last(iterable $array)
    {
        $array = self::makeArray($array);
        return end($array);
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
     * @template A
     * @template B
     * @param A[] $array 
     * @param callable(A $value, mixed $key): B $callable
     * @return B[]
     */
    public static function map(iterable $array, callable $callable): array
    {
        $array = self::makeArray($array);
        $result = [];

        foreach ($array as $key => $value) {
            $result[$key] = call_user_func($callable, $value, $key);
        }

        return $result;
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
     * @template A
     * @template K
     * @param A[] $array 
     * @param callable(A $value, mixed $key): K $callable
     * @return array<K, A>
     */
    public static function mapKeys(iterable $array, callable $callable): array
    {
        $array = self::makeArray($array);
        $result = [];

        foreach ($array as $key => $value) {
            $result[call_user_func($callable, $value, $key)] = $value;
        }

        return $result;
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
     * @template A
     * @var A[][] $arrays
     * @return A[]
     */
    public static function merge(...$arrays): array
    {
        foreach ($arrays as $i => $array) {
            $arrays[$i] = self::makeArray($array);
        }

        return array_merge(...$arrays);
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
     * @template A
     * @param A[] $array 
     * @param A[] $values
     * @return A[]
     */
    public static function push(iterable $array, ...$values): array
    {
        $array = self::makeArray($array);
        $result = $array; // make copy
        array_push($result, ...$values);
        return $result;
    }

    /**
     * Iteratively reduce the array to a single value using a callback function.
     * 
     * @template A
     * @template B
     * @param A[] $array 
     * @param callable(B|null $carry, A $value, mixed $key): B $callable
     * @param B|null $initial If the optional initial is available, it will be used at the beginning of the process, or as a final result in case the array is empty.
     * @return B
     */
    public static function reduce(iterable $array, callable $callable, $initial = null)
    {
        $carry = $initial;

        foreach ($array as $key => $value) {
            $carry = call_user_func($callable, $carry, $value, $key);
        }

        return $carry;
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
     * @template A
     * @param A[] $array 
     * @param callable(A $value, mixed $key): bool $callable
     * @return A|null
     */
    public static function search(iterable $array, callable $callable)
    {
        foreach ($array as $key => $value) {
            if (call_user_func($callable, $value, $key) === true) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Extract a slice of the array, include `$length` items, and starting from `$offset`.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $even = Ar::slice(['a', 'b', 'c', 'd'], 1, 2); 
     * $even = Ar::wrap(['a', 'b', 'c', 'd'])
     *     ->slice(1, 2)
     *     ->unwrap();
     * // Result: ['b', 'c']
     * ```
     * 
     * @template A
     * @param A[] $array 
     * @param int $offset
     *      If offset is non-negative, the sequence will start at that offset in the array.
     *      If offset is negative, the sequence will start that far from the end of the array. 
     * @param ?int $length 
     *      If length is given and is positive, then the sequence will have up to that many elements
     *      in it.
     *      If the array is shorter than the length, then only the available array elements will be
     *      present.
     *      If length is given and is negative then the sequence will stop that many elements from 
     *      the end of the array.
     *      If it is omitted, then the sequence will have everything from offset up until the end of
     *      the array.
     * @return A[]
     */
    public static function slice(
        iterable $array,
        int $offset,
        ?int $length = null
    ): array {
        $array = self::makeArray($array);


        $result = array_slice($array, $offset, $length, !array_is_list($array));

        return $result;
    }
    
    
    /**
     * Remove a portion of the array and replace it with something else.
     * Other than the default php function, this returns the changed array, not the extracted
     * elements.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * 
     * $even = Ar::splice(['a', 'b', 'c', 'd'], 1, 1, ['q', 'x']); 
     * $even = Ar::wrap(['a', 'b', 'c', 'd'])
     *     ->splice(1, 1, ['q', 'x'])
     *     ->unwrap();
     * // Result: ['a', 'q', 'x', 'c', 'd']
     * ```
     * 
     * @template A
     * @param A[] $array 
     * @param int $offset
     *      If offset is positive then the start of the removed portion is at that offset from 
     *      the beginning of the array. 
     * 
     *      If offset is negative then the start of the removed portion is at that offset from 
     *      the end of the array. 
     * @param ?int $length 
     *      If length is omitted, removes everything from offset to the end of the array.
     *
     *      If length is specified and is positive, then that many elements will be removed.
     *       
     *      If length is specified and is negative, then the end of the removed portion will be 
     *      that many elements from the end of the array.
     *       
     *      If length is specified and is zero, no elements will be removed. 
     * @param A[] $replacement
     *      If replacement array is specified, then the removed elements are replaced with elements 
     *      from this array.
     * 
     *      If offset and length are such that nothing is removed, then the elements from the 
     *      replacement array are inserted in the place specified by the offset. 
     *
     *      If replacement is just one element it is not necessary to put array() or square brackets 
     *      around it, unless the element is an array itself, an object or null. 
     *      
     *      Note: Keys in the replacement array are not preserved.
     * @return A[] Other than the default php function, this returns the changed array, not the 
     *     extracted elements.
     */
    public static function splice(
        iterable $array,
        int $offset,
        ?int $length = null,
        mixed $replacement = []
    ): array {
        $array = self::makeArray($array);

        array_splice($array, $offset, $length, $replacement);

        return $array;
    }

    /**
     * Sort an array by values using a user-defined comparison function.
     * 
     * This function assigns new keys to the elements in array. It will remove any existing keys that may have been assigned.
     * 
     * 
     * @template A
     * @param A[] $array 
     * @param callable(A $valueA, A $valueB): int $callable    
     *                              Return an integer smaller then, equal to,
     *                              or larger than 0 to indicate that $valueA is less
     *                              then, equal to, or larger than $valueB.
     * @return A[]
     */
    public static function sort(iterable $array, callable $callable): array
    {
        $array = self::makeArray($array);
        usort($array, $callable);

        return $array;
    }

    // private static function testIterable($array)
    // {
    //     if (!is_iterable($array)) {
    //         $type = gettype($array);
    //         if ($type == "object") {
    //             $type = get_class($array);
    //         }
    //         throw new InvalidArgumentException('You must pass an array or iterable. You passed: ' . $type);
    //     }
    // }

    // public static function is_iterable($var)
    // {
    //     return \is_array($var) || $var instanceof \Traversable;
    // }

    // public static function toArray(iterable $iterable): array {
    //     if (is_array($iterable)) return $iterable;
    //     return iterator_to_array($iterable);
    // }

    /**
     * Remove duplicate values from array.
     * Keys are preserved only when `array_is_list($array)` returns false;
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
     * @template A
     * @param A[] $array 
     * @param int $flags The optional second parameter flags may be used to modify the sorting behavior using these values:
     *     Sorting type flags:
     *     
     *     SORT_REGULAR - compare items normally (don't change types)
     *     SORT_NUMERIC - compare items numerically
     *     SORT_STRING - compare items as strings
     *     SORT_LOCALE_STRING - compare items as strings, based on the current locale.
     * @return A[]
     */
    public static function unique(iterable $array, int $flags = SORT_REGULAR): array
    {
        $array = self::makeArray($array);

        $result = array_unique(self::makeArray($array), $flags);
        if (array_is_list($array)) {
            return array_values($result);
        } else {
            return $result;
        }
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
     * @template A
     * @param A[] $array 
     * @param A[] $values 
     * @return A[]
     */
    public static function unshift(iterable $array, ...$values): array
    {
        $array = self::makeArray($array);
        $result = $array; // make copy
        array_unshift($result, ...$values);
        return $result;
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
     * @template A
     * @param array<mixed, A> $array 
     * @return array<int, A>
     */
    public static function values(iterable $array): array
    {
        $array = self::makeArray($array);
        return array_values($array);
    }
}

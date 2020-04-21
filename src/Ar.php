<?php

namespace FriendlyPixel\Ar;

use InvalidArgumentException;

class Ar
{
    public static function new(/* iterable */$array): ArFluent
    {
        return new ArFluent($array);
    }

    /* === Functions, sorted alphabetically === */

    /**
     * Count how many items there are in the array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * $count = Ar::count([1, 2, 3]); 
     * $count = Ar::new([1, 2, 3])
     *     ->count()
     * ;
     * // Result: 3
     * ```
     */
    public static function count(/* iterable */$array): int
    {
        $array = self::makeArray($array);
        return count($array);
    }

    /**
     * Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
     * Keys are preserved, this means that the returned array will be associative. Use `filterValues` if you want a sequential result.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * $even = Ar::filter([1, 2, 3, 12], function($value, $key) { return $value % 2 == 0; }); 
     * $even = Ar::new([1, 2, 3, 12])
     *     ->filter(function($value, $key) { return $value % 2 == 0; })
     *     ->unwrap()
     * ;
     * // Result: [1 => 2, 3 => 12]
     * ```
     * 
     * @param callable $callable ($value, $key): bool
     * @return mixed[]
     */
    public static function filter(/* iterable */$array, callable $callable): array
    {
        self::testIterable($array);
        $result = [];

        foreach ($array as $key => $value) {
            if (call_user_func($callable, $value, $key) === true) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
     * Keys are not preserved, the returned array is sequential. Use `filter` to preserve keys.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * $even = Ar::filter([1, 2, 3, 12], function($value, $key) { return $value % 2 == 0; }); 
     * $even = Ar::new([1, 2, 3, 12])
     *     ->filter(function($value, $key) { return $value % 2 == 0; })
     *     ->unwrap()
     * ;
     * // Result: [2, 12]
     * ```
     * 
     * @param callable $callable ($value, $key): bool
     * @return mixed[]
     */
    public static function filterValues(/* iterable */$array, callable $callable): array
    {
        self::testIterable($array);
        $result = [];

        foreach ($array as $key => $value) {
            if (call_user_func($callable, $value, $key) === true) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * The flat() method creates a new array with all sub-array elements concatenated into it recursively up to the specified depth.
     * @param int $depth To what level to flatten the array. Default: 1
     * @return mixed[]
     */
    public static function flat(/* iterable */$array, int $depth = 1)
    {
        self::testIterable($array);
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
     * @param callable $callable ($value, $key)
     * @return mixed[]
     */
    public static function forEach(/* iterable */$array, callable $callable): array
    {
        self::testIterable($array);
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
     * $result = Ar::implode(['a', 'b', 'c'], ','); 
     * $result = Ar::new(['a', 'b', 'c'])
     *     ->implode(',')
     * ;
     * // result: "a,b,c"
     * ```
     */
    public static function implode(/* iterable */$array, string $glue = ''): string
    {
        $array = self::makeArray($array);
        return implode($glue, $array);
    }

    /**
     * Return the keys of an array as a sequential array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * $result = Ar::keys([3 => 'a', 'foo' => 'b', 1 => 'c']); 
     * $result = Ar::new([3 => 'a', 'foo' => 'b', 1 => 'c'])->keys();
     * // result: [3, 'foo', 1]
     * ```
     * 
     * @return mixed[]
     */
    public static function keys(/* iterable */$array): array
    {
        $array = self::makeArray($array);
        return array_keys($array);
    }

    public static function makeArray(/* iterable */$array)
    {
        if (is_array($array)) {
            return $array;
        } elseif (is_iterable($array)) {
            return iterator_to_array($array, true);
        } else {
            $type = gettype($array);
            if ($type == "object") {
                $type = get_class($array);
            }
            throw new InvalidArgumentException('You must pass an array or iterable. You passed: ' . $type);
        }
    }

    /**
     * Transform values.
     * Pass every value, key into a user-supplied callable, and put the returned value into the result array.
     * Keys are preserved.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * $numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
     * $numbers = Ar::new([1, 2, 3])
     *     ->map(function($value, $key) { return $value * 2; })
     *     ->unwrap()
     * ;
     * // Result: [2, 4, 6]
     * ```
     * 
     * @param callable $callable ($value, $key): mixed
     * @return mixed[]
     */
    public static function map(/* iterable */$array, callable $callable): array
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
     * $numbers = Ar::mapKeys([1, 2, 3], function($value, $key) { return $key * 2; }); 
     * $numbers = Ar::new([1, 2, 3])
     *     ->mapKeys(function($value, $key) { return $key * 2; })
     *     ->unwrap()
     * ;
     * // Result: [0 => 2, 2 => 2, 4 => 3]
     * ```
     * 
     * @param callable $callable ($value, $key): mixed
     * @return mixed[]
     */
    public static function mapKeys(/* iterable */$array, callable $callable): array
    {
        $array = self::makeArray($array);
        $result = [];

        foreach ($array as $key => $value) {
            $result[call_user_func($callable, $value, $key)] = $value;
        }

        return $result;
    }


    /**
     * Iteratively reduce the array to a single value using a callback function.
     * 
     * @param mixed|null $initial If the optional initial is available, it will be used at the beginning of the process, or as a final result in case the array is empty.
     * @param callable $callable function($carry, $value, $key): mixed
     * @return mixed
     */
    public static function reduce(/* iterable */$array, callable $callable, $initial = null)
    {
        self::testIterable($array);
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
     * $found = Ar::search([ ['a' => 1], ['a' => 8], ['a' => 3] ], function($value, $key) { return $value['a'] == 3; }); 
     * $found = Ar::new([ ['a' => 1], [], ['a' => 3] ])
     *     ->search(function($value, $key) { return $value['a'] == 3; })
     * ;
     * // Result: ['a' => 3]
     * ```
     * 
     * @param callable $callable ($value, $key): bool
     * @return mixed
     */
    public static function search(/* iterable */$array, callable $callable)
    {
        self::testIterable($array);

        foreach ($array as $key => $value) {
            if (call_user_func($callable, $value, $key) === true) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Sort an array by values using a user-defined comparison function.
     * 
     * @param callable $callable    function($valueA, $valueB): int 
     *                              Return an integer smaller then, equal to,
     *                              or larger than 0 to indicate that $valueA is less
     *                              then, equal to, or larger than $valueB.
     * @return mixed[]
     */
    public static function sort(/* iterable */$array, callable $callable): array
    {
        $array = self::makeArray($array);
        usort($array, $callable);

        return $array;
    }

    private static function testIterable($array)
    {
        if (!is_iterable($array)) {
            $type = gettype($array);
            if ($type == "object") {
                $type = get_class($array);
            }
            throw new InvalidArgumentException('You must pass an array or iterable. You passed: ' . $type);
        }
    }

    /**
     * Return the values of an array as a sequential array.
     * 
     * ```php
     * use FriendlyPixel\Ar\Ar;
     * $result = Ar::values([3 => 'a', 'foo' => 'b', 1 => 'c']); 
     * $result = Ar::new([3 => 'a', 'foo' => 'b', 1 => 'c'])->values();
     * // result: [0 => 'a', 1 => 'b', 2 => 'c']
     * ```
     * 
     * @return mixed[]
     */
    public static function values(/* iterable */$array): array
    {
        $array = self::makeArray($array);
        return array_values($array);
    }
}

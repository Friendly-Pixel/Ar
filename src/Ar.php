<?php

namespace Frontwise\Ar;

use InvalidArgumentException;

class Ar
{
    public function __construct(/* iterable */$array = null)
    {
        return new ArFluent($array);
    }

    public static function new(/* iterable */$array): ArFluent
    {
        return new ArFluent($array);
    }

    /* === Functions, sorted alphabetically === */

    /**
     * Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
     * Keys are preserved.
     * 
     * ```php
     * use Frontwise\Ar\Ar;
     * $even = Ar::filter([1, 2, 3], function($value, $key) { return $value % 2 == 0; }); 
     * $even = Ar::new([1, 2, 3])
     *     ->filter(function($value, $key) { return $value % 2 == 0; })
     *     ->unwrap()
     * ;
     * // Result: [0 => 2, 2 => 2, 4 => 3]
     * ```
     * 
     * @param callable $callable callable($value, $key): bool
     * @return mixed[]
     */
    public static function filter(/* iterable */$array, callable $callable): array
    {
        $array = self::makeArray($array);
        $result = [];

        foreach ($array as $key => $value) {
            if (call_user_func($callable, $value, $key)) {
                $result[$key] = $value;
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
        $array = self::makeArray($array);
        $result = [];

        self::_flat($result, $array, $depth);

        return $result;
    }

    private static function _flat(array &$result, $input, int $depth)
    {
        $array = self::makeArray($array);
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
     * @param callable $callable callable($value, $key)
     * @return mixed[]
     */
    public static function forEach(/* iterable */$array, callable $callable): array
    {
        $array = self::makeArray($array);
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
     * use Frontwise\Ar\Ar;
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
     * Transform values.
     * Pass every value, key into a user-supplied callable, and put the returned value into the result array.
     * Keys are preserved.
     * 
     * ```php
     * use Frontwise\Ar\Ar;
     * $numbers = Ar::map([1, 2, 3], function($value, $key) { return $value * 2; }); 
     * $numbers = Ar::new([1, 2, 3])
     *     ->map(function($value, $key) { return $value * 2; })
     *     ->unwrap()
     * ;
     * // Result: [2, 4, 6]
     * ```
     * 
     * @param callable $callable callable($value, $key): mixed
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
     * use Frontwise\Ar\Ar;
     * $numbers = Ar::mapKeys([1, 2, 3], function($value, $key) { return $key * 2; }); 
     * $numbers = Ar::new([1, 2, 3])
     *     ->mapKeys(function($value, $key) { return $key * 2; })
     *     ->unwrap()
     * ;
     * // Result: [0 => 2, 2 => 2, 4 => 3]
     * ```
     * 
     * @param callable $callable callable($value, $key): mixed
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
        $array = self::makeArray($array);
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
     * use Frontwise\Ar\Ar;
     * $found = Ar::search([ ['a' => 1], ['a' => 8], ['a' => 3] ], function($value, $key) { return $value['a'] == 3; }); 
     * $found = Ar::new([ ['a' => 1], [], ['a' => 3] ])
     *     ->search(function($value, $key) { return $value['a'] == 3; })
     * ;
     * // Result: ['a' => 3]
     * ```
     * 
     * @param callable $callable callable($value, $key): bool
     * @return mixed
     */
    public static function search(/* iterable */$array, callable $callable)
    {
        $array = self::makeArray($array);

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
}

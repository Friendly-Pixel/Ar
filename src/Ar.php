<?php

namespace Frontwise\Ar;

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

    /**
     * Transform values.
     * Pass every value, key into a user-supplied callable, and put the returned value into the result array.
     * Keys are preserved.
     * @return mixed[]
     */
    public static function map(/* iterable */$array, callable $callable): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $result[$key] = call_user_func($callable, $value, $key);
        }

        return $result;
    }

    /**
     * Transform keys.
     * Pass every value, key and key into a user-supplied callable, and use the returned value as key in the result array.
     * @return mixed[]
     */
    public static function mapKeys(/* iterable */$array, callable $callable): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $result[call_user_func($callable, $value, $key)] = $value;
        }

        return $result;
    }

    /**
     * Pass every value, key into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
     * Keys are preserved.
     * @return mixed[]
     */
    public static function filter(/* iterable */$array, callable $callable): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (call_user_func($callable, $value, $key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Return the first value for which the callable returns `true`.
     * Returns `null` otherwise.
     * @return mixed
     */
    public static function search(/* iterable */$array, callable $callable)
    {
        foreach ($array as $key => $value) {
            if (call_user_func($callable, $value, $key) === true) {
                return $value;
            }
        }

        return null;
    }

    public static function sort(/* iterable */$array, callable $callable): array
    {
        usort($array, $callable);

        return $array;
    }

    /**
     * Walk over every value, key.
     * Pass every value, key into a user-supplied callable.
     * @return mixed[] the array
     */
    public static function forEach(/* iterable */$array, callable $callable): array
    {
        foreach ($array as $key => $value) {
            call_user_func($callable, $value, $key);
        }

        return $array;
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
        $carry = $initial;

        foreach ($array as $key => $value) {
            $carry = call_user_func($callable, $carry, $value, $key);
        }

        return $carry;
    }

    /**
     * The flat() method creates a new array with all sub-array elements concatenated into it recursively up to the specified depth.
     */
    public static function flat(/* iterable */$array, int $depth = 1)
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
}

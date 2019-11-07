<?php

namespace Frontwise\Ar;

class Ar {
    public function __construct(iterable $array = null) {
        return new ArFluent($array);
    }
    
    public function new(iterable $array): ArFluent {
        return new ArFluent($array);
    }
    
    /**
     * Transform values.
     * Pass every value, key into a user-supplied callable, and put the returned value into the result array.
     * Keys are preserved.
     * @return mixed[]
     */
    public static function map(iterable $array, callable $callable): array {
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
    public static function mapKeys(iterable $array, callable $callable): array {
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
    public static function filter(iterable $array, callable $callable): array {
        $result = [];
        
        foreach ($array as $key => $value) {
            if (call_user_func($callable, $value, $key)) {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }
}



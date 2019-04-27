<?php

namespace Frontwise;

class Ar {
    public function __construct(array $array = null) {
        return new ArFluent($array);
    }
    
    /**
     * Transform values.
     * Pass every item into a user-supplied callable, and put the returned value into the result array.
     * Keys are preserved.
     * @return mixed[]
     */
    public static function map(array $array, callable $callable): array {
        $result = [];
        
        foreach ($array as $key => $value) {
            $result[$key] = call_user_func($callable, $value, $key);
        }
        
        return $result;
    }
    
    /**
     * Transform keys.
     * Pass every item and key into a user-supplied callable, and use the returned value as key in the result array.
     * @return mixed[]
     */
    public static function mapKeys(array $array, callable $callable): array {
        $result = [];
        
        foreach ($array as $key => $value) {
            $result[call_user_func($callable, $value, $key)] = $value;
        }
        
        return $result;
    }
    
    /**
     * Pass every item into a user-supplied callable, and only put the item into the result array if the returned value is `true`.
     * Keys are preserved.
     * @return mixed[]
     */
    public static function filter(array $array, callable $callable): array {
        $result = [];
        
        foreach ($array as $key => $value) {
            if (call_user_func($callable, $value, $key)) {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }
    
}

class ArFluent implements \IteratorAggregate {
    public $array = [];
    
    public function __construct(array $array = null) {
        if ($array) {
            $this->array = $array;
        }
    }
    
    public function map(callable $callable): self {
        $this->array = Ar::map($this->array, $callable);
        return $this;
    }
    
    public function mapKeys(callable $callable): self {
        $this->array = Ar::mapKeys($this->array, $callable);
        return $this;
    }
    
    public function filter(callable $callable): self {
        $this->array = Ar::filter($this->array, $callable);
        return $this;
    }
    
    public function unwrap() {
        return $this->array;
    }
    
    /* IteratorAggregate implementation */
    public function getIterator() {
        return new \ArrayIterator($this->array);
    }
}

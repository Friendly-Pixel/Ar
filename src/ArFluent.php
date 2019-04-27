<?php

namespace Frontwise;

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
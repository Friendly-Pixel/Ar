<?php

namespace Frontwise\Ar;

class ArFluent implements \IteratorAggregate, \ArrayAccess
{
    /** @var array $array */
    private $array = [];

    public function __construct(/* iterable */$array = null)
    {
        if ($array) {
            if (is_array($array)) {
                $this->array = $array;
            } else {
                $this->array = iterator_to_array($array, true);
            }
        }
    }

    /* ======= */

    public function map(callable $callable): self
    {
        return new static(Ar::map($this->array, $callable));
    }

    public function mapKeys(callable $callable): self
    {
        return new static(Ar::mapKeys($this->array, $callable));
    }

    public function filter(callable $callable): self
    {
        return new static(Ar::filter($this->array, $callable));
    }

    public function sort(callable $callable): self
    {
        return new static(Ar::sort($this->array, $callable));
    }

    public function search(callable $callable)
    {
        return Ar::search($this->array, $callable);
    }

    public function forEach(callable $callable): self
    {
        Ar::forEach($this->array, $callable);
        return $this;
    }

    public function reduce(callable $callable, $initial = null)
    {
        return Ar::reduce($this->array, $callable, $initial);
    }

    public function flat($depth = 1)
    {
        return new static(Ar::flat($this->array, $depth));
    }

    /* ======= Fluent only ======= */

    /**
     * Return the underlying array.
     * @return array 
     */
    public function unwrap()
    {
        return $this->array;
    }

    /**
     * Return the underlying array.
     * Alias for `ArFluent::unwrap`
     * @return array 
     */
    public function toArray()
    {
        return $this->unwrap();
    }

    /**
     * Join all values into a big string, using `$glue` as separator.
     * `$glue` is optional.
     * @return string
     */
    public function implode(string $glue = ''): string
    {
        return implode($glue, $this->array);
    }

    /* IteratorAggregate implementation */

    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }

    /* Arrayaccess implementation */

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
}

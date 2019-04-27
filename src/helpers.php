<?php

use Frontwise\ArFluent;

if (! function_exists('ar')) {
    /**
     * Create a new Ar object with the given items
     *
     * @param mixed[] $iterable
     * @return \Adbar\Dot
     */
    function ar(iterable $iterable): ArFluent
    {
        return new ArFluent($iterable);
    }
}
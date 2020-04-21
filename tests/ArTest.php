<?php

declare(strict_types=1);

namespace Frontwise\Ar\Test;

use Frontwise\Ar\Ar;
use Frontwise\Ar\ArFluent;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

final class ArTest extends TestCase
{
    /**
     * Test all functions that return an array.
     * 
     * @dataProvider returnsArrayProvider
     */
    public function testReturnsArrayFunc(string $funcName, array $input, array $expected, callable $callable)
    {
        // Functional
        $a = $input;
        $b = Ar::$funcName($a, $callable);
        $this->assertEquals($expected, $b);

        // Iterable
        $it = new MyIterable($a);
        $b = Ar::$funcName($a, $callable);
        $this->assertEquals($expected, $b);

        // Fluent
        $b = Ar::new($a)
            ->$funcName($callable)
            ->unwrap();
        $this->assertEquals($expected, $b);
    }

    public function returnsArrayProvider()
    {
        $result = [];

        // map
        foreach ([
            function ($v) {
                return $v + $v;
            },
            [$this, 'timesTwo'],
        ] as $callable) {
            $result[] = ['map', [1, 2, 3], [2, 4, 6], $callable];
            $result[] = ['map', ['a' => 1, 'b' => 2, 'c' => 3], ['a' => 2, 'b' => 4, 'c' => 6], $callable];
            $result[] = ['map', [12 => 1, 81 => 2, 13 => 3], [12 => 2, 81 => 4, 13 => 6], $callable];
        }

        // mapKeys
        foreach ([
            function ($v, $k) {
                return $k * 2;
            },
            [$this, 'keyTimesTwo'],
        ] as $callable) {
            $result[] = ['mapKeys', [1, 2, 3], [0 => 1, 2 => 2, 4 => 3], $callable];
            $result[] = ['mapKeys', [12 => 1,  81 => 2, 13 => 3], [24 => 1, 162 => 2, 26 => 3], $callable];
        }

        // filter
        foreach ([
            function ($v) {
                return $v % 2 == 0;
            },
            [$this, 'isEven'],
        ] as $callable) {
            $result[] = [
                'filter',
                [1, 2, 3, 12],
                [1 => 2, 3 => 12],
                $callable
            ];
            $result[] = [
                'filter',
                ['a' => 1,  'b' => 2, 'c' => 3],
                ['b' => 2],
                $callable
            ];
            $result[] = [
                'filter',
                [12 => 1,  81 => 2, 13 => 3],
                [81 => 2],
                $callable
            ];
        }

        // Sort
        foreach ([
            function ($a, $b) {
                return $a - $b;
            },
        ] as $callable) {
            $result[] = [
                'sort',
                [4, 8, 8, 1, 3, 6],
                [1, 3, 4, 6, 8, 8],
                $callable
            ];
            $result[] = [
                'sort',
                [],
                [],
                $callable
            ];
        }


        return $result;
    }

    /**
     * Test all functions that return a value.
     * 
     * @dataProvider returnsValueProvider
     */
    public function testReturnsValueFunc(string $funcName, array $input, $expected, callable $callable)
    {
        // Functional
        $a = $input;
        $b = Ar::$funcName($a, $callable);
        $this->assertEquals($expected, $b);

        // Iterable
        $it = new MyIterable($a);
        $b = Ar::$funcName($a, $callable);
        $this->assertEquals($expected, $b);

        // Fluent
        $b = Ar::new($a)
            ->$funcName($callable);
        $this->assertEquals($expected, $b);
    }

    public function returnsValueProvider()
    {
        $result = [];

        // search
        $target = ['a' => 2, 'c' => 3];
        foreach ([
            function ($v) {
                return ($v['a'] ?? 0) == 2;
            },
        ] as $callable) {
            $result[] = [
                'search',
                [[], ['a' => 1], $target],
                $target,
                $callable
            ];
            $result[] = [
                'search',
                [[], ['a' => 1], ['b' => 8]],
                null,
                $callable
            ];
        }

        return $result;
    }

    /* Callables */

    public function timesTwo($value)
    {
        return $value * 2;
    }

    public function keyTimesTwo($value, $key)
    {
        return $key * 2;
    }

    public function isEven($value)
    {
        return $value % 2 == 0;
    }
}

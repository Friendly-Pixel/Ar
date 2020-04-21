<?php

declare(strict_types=1);

namespace Frontwise\Ar\Test;

use Frontwise\Ar\Ar;
use Frontwise\Ar\ArFluent;
use Frontwise\Ar\Test\Traits\BaseTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

final class ArTest extends TestCase
{
    use BaseTrait;

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
        $b = Ar::$funcName($it, $callable);
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
            $result[] = [
                'filter',
                [],
                [],
                $callable
            ];
        }

        // filterValues
        foreach ([
            function ($v) {
                return $v % 2 == 0;
            },
            [$this, 'isEven'],
        ] as $callable) {
            $result[] = [
                'filterValues',
                [1, 2, 3, 12],
                [2, 12],
                $callable
            ];
            $result[] = [
                'filterValues',
                ['a' => 1,  'b' => 2, 'c' => 3],
                [2],
                $callable
            ];
            $result[] = [
                'filterValues',
                [12 => 1,  81 => 2, 13 => 3],
                [2],
                $callable
            ];
            $result[] = [
                'filterValues',
                [],
                [],
                $callable
            ];
        }

        // keys
        $result[] = [
            'keys',
            [3 => 'a', 'foo' => 'b', 1 => 'c'],
            [3, 'foo', 1],
            $callable
        ];
        $result[] = [
            'keys',
            [],
            [],
            $callable
        ];

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

        // values
        $result[] = [
            'values',
            [3 => 'a', 'foo' => 'b', 1 => 'c'],
            [0 => 'a', 1 => 'b', 2 => 'c'],
            $callable
        ];
        $result[] = [
            'values',
            [3 => 'a', 'foo' => 'b', 1 => 'c'],
            ['a', 'b', 'c'],
            $callable
        ];
        $result[] = [
            'values',
            [],
            [],
            $callable
        ];

        return $result;
    }

    /**
     * Test all functions that return a value.
     * 
     * @dataProvider returnsValueProvider
     */
    public function testReturnsValueFunc(string $funcName, /* array */ $input, $expected, $param1)
    {
        // Functional
        $a = $input;
        $b = Ar::$funcName($a, $param1);
        $this->assertEquals($expected, $b);

        // Iterable
        $it = new MyIterable($a);
        $b = Ar::$funcName($a, $param1);
        $this->assertEquals($expected, $b);

        // Fluent
        $b = Ar::new($a)
            ->$funcName($param1);
        $this->assertEquals($expected, $b);
    }

    public function returnsValueProvider()
    {
        $tests = [];

        // search
        $target = ['a' => 2, 'c' => 3];
        $callable = function ($v) {
            return ($v['a'] ?? 0) == 2;
        };
        $tests[] = [
            'search', // funcName
            [[], ['a' => 1], $target], // input
            $target, // expected
            $callable // callable
        ];
        $tests[] = [
            'search', // funcName
            [[], ['a' => 1], ['b' => 8]], // input
            null, // expected
            $callable // callable
        ];

        // implode
        $tests[] = [
            'implode', // funcName
            [1, 2, 3], // input
            '1 - 2 - 3', // expected
            ' - ' // glue
        ];
        $tests[] = [
            'implode', // funcName
            [], // input
            '', // expected
            ' - ' // glue
        ];
        $tests[] = [
            'implode', // funcName
            ['a', 'b' => 10], // input
            'a,10', // expected
            ',' // glue
        ];

        return $tests;
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

<?php

declare(strict_types=1);

namespace FriendlyPixel\Ar\Test;

use FriendlyPixel\Ar\Ar;
use FriendlyPixel\Ar\ArFluent;
use FriendlyPixel\Ar\Test\Traits\BaseTrait;
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
    public function testReturnsArrayFunc(string $funcName, array $input, array $expected, $param0 = null, $param1 = null)
    {
        // Functional
        $a = $input;
        $b = Ar::$funcName($a, $param0, $param1);
        $this->assertEquals($expected, $b);

        // Iterable
        $it = new MyIterable($a);
        $b = Ar::$funcName($it, $param0, $param1);
        $this->assertEquals($expected, $b);

        // Fluent
        $b = Ar::wrap($a)
            ->$funcName($param0, $param1)
            ->unwrap();
        $this->assertEquals($expected, $b);

        // Make sure source array was never modified
        $this->assertEquals($input, $a);
    }

    public function returnsArrayProvider()
    {
        $tests = [];

        // filter
        foreach ([
            function ($v) {
                return $v % 2 == 0;
            },
            [$this, 'isEven'],
        ] as $callable) {
            $tests[] = [
                'filter',
                [1, 2, 3, 12],
                [1 => 2, 3 => 12],
                $callable
            ];
            $tests[] = [
                'filter',
                ['a' => 1,  'b' => 2, 'c' => 3],
                ['b' => 2],
                $callable
            ];
            $tests[] = [
                'filter',
                [12 => 1,  81 => 2, 13 => 3],
                [81 => 2],
                $callable
            ];
            $tests[] = [
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
            $tests[] = [
                'filterValues',
                [1, 2, 3, 12],
                [2, 12],
                $callable
            ];
            $tests[] = [
                'filterValues',
                ['a' => 1,  'b' => 2, 'c' => 3],
                [2],
                $callable
            ];
            $tests[] = [
                'filterValues',
                [12 => 1,  81 => 2, 13 => 3],
                [2],
                $callable
            ];
            $tests[] = [
                'filterValues',
                [],
                [],
                $callable
            ];
        }

        // flat
        $tests[] = [
            'flat',
            [['a', 'b'], ['c']],
            ['a', 'b', 'c'],
            1
        ];

        // keys
        $tests[] = [
            'keys',
            [3 => 'a', 'foo' => 'b', 1 => 'c'],
            [3, 'foo', 1],
            $callable
        ];
        $tests[] = [
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
            $tests[] = ['map', [1, 2, 3], [2, 4, 6], $callable];
            $tests[] = ['map', ['a' => 1, 'b' => 2, 'c' => 3], ['a' => 2, 'b' => 4, 'c' => 6], $callable];
            $tests[] = ['map', [12 => 1, 81 => 2, 13 => 3], [12 => 2, 81 => 4, 13 => 6], $callable];
        }

        // mapKeys
        foreach ([
            function ($v, $k) {
                return $k * 2;
            },
            [$this, 'keyTimesTwo'],
        ] as $callable) {
            $tests[] = ['mapKeys', [1, 2, 3], [0 => 1, 2 => 2, 4 => 3], $callable];
            $tests[] = ['mapKeys', [12 => 1,  81 => 2, 13 => 3], [24 => 1, 162 => 2, 26 => 3], $callable];
        }

        // push
        $tests[] = [
            'push',
            [1, 2],
            [1, 2, 3, 4],
            3,
            4,
        ];
        $tests[] = [
            'push',
            ['a' => 'foo', 'b' => 'bar'],
            ['a' => 'foo', 'b' => 'bar', 3, 4],
            3,
            4,
        ];
        $tests[] = [
            'push',
            ['a' => 'foo', 'b' => 'bar'],
            ['a' => 'foo', 'b' => 'bar', 0 => 3, 1 => 4],
            3,
            4,
        ];

        // sort
        foreach ([
            function ($a, $b) {
                return $a - $b;
            },
        ] as $callable) {
            $tests[] = [
                'sort',
                [4, 8, 8, 1, 3, 6],
                [1, 3, 4, 6, 8, 8],
                $callable
            ];
            $tests[] = [
                'sort',
                [],
                [],
                $callable
            ];
        }

        // unshift
        $tests[] = [
            'unshift',
            [3, 4],
            [1, 2, 3, 4],
            1,
            2,
        ];

        // values
        $tests[] = [
            'values',
            [3 => 'a', 'foo' => 'b', 1 => 'c'],
            [0 => 'a', 1 => 'b', 2 => 'c'],
            $callable
        ];
        $tests[] = [
            'values',
            [3 => 'a', 'foo' => 'b', 1 => 'c'],
            ['a', 'b', 'c'],
            $callable
        ];
        $tests[] = [
            'values',
            [],
            [],
            $callable
        ];

        return $tests;
    }

    /**
     * Test all functions that return a value.
     * 
     * @dataProvider returnsValueProvider
     */
    public function testReturnsValueFunc(string $funcName, /* array */ $input, $expected, $param0 = null)
    {
        // Functional
        $a = $input;
        $b = Ar::$funcName($a, $param0);
        $this->assertEquals($expected, $b);

        // Iterable
        $it = new MyIterable($a);
        $b = Ar::$funcName($a, $param0);
        $this->assertEquals($expected, $b);

        // Fluent
        $b = Ar::wrap($a)
            ->$funcName($param0);
        $this->assertEquals($expected, $b);

        // Make sure source array was never modified
        $this->assertEquals($input, $a);
    }

    public function returnsValueProvider()
    {
        $tests = [];

        // count
        $tests[] = [
            'count', // funcName
            [1, 2, 3], // input
            3, // expected
            null
        ];
        $tests[] = [
            'count', // funcName
            [], // input
            0, // expected
            null
        ];

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

        // first
        $tests[] = [
            'first', // funcName
            [1, 2, 3], // input
            1, // expected
        ];
        $tests[] = [
            'first', // funcName
            [1 => 'bar', 0 => 'foo'], // input
            'bar', // expected
        ];
        $tests[] = [
            'first', // funcName
            ['bar', 'foo'], // input
            'bar', // expected
        ];
        $tests[] = [
            'first', // funcName
            ['a' => 'foo', 3 => 'bar', 1 => 'quux'], // input
            'foo', // expected
        ];

        // last
        $tests[] = [
            'last', // funcName
            [1, 2, 3], // input
            3, // expected
        ];
        $tests[] = [
            'last', // funcName
            [1 => 'bar', 0 => 'foo'], // input
            'foo', // expected
        ];
        $tests[] = [
            'last', // funcName
            ['bar', 'foo'], // input
            'foo', // expected
        ];
        $tests[] = [
            'last', // funcName
            ['a' => 'foo', 3 => 'bar', 1 => 'quux'], // input
            'quux', // expected
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

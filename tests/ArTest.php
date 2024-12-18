<?php

declare(strict_types=1);

namespace FriendlyPixel\Ar\Test;

use FriendlyPixel\Ar\Ar;
use FriendlyPixel\Ar\ArFluent;
use FriendlyPixel\Ar\Test\Traits\BaseTrait;
use InvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ArTest extends TestCase
{
    use BaseTrait;

    /**
     * Test all functions that return an array.
     * 
     * @dataProvider returnsArrayProvider
     */
    public function testReturnsArrayFunc(string $funcName, array $input, array $expected, $param0 = null, $param1 = null, $param2 = null)
    {
        $params = [];
        if ($param0 !== null) {
            $params = [$param0];
        }
        if ($param1 !== null) {
            $params = [$param0, $param1];
        }
        if ($param2 !== null) {
            $params = [$param0, $param1, $param2];
        }

        // Functional
        $a = $input;
        $b = Ar::$funcName($a, ...$params);
        $this->assertEquals($expected, $b);

        // Iterable
        $it = new MyIterable($a);
        $b = Ar::$funcName($it, ...$params);
        $this->assertEquals($expected, $b);

        // Fluent
        $b = Ar::wrap($a)
            ->$funcName(...$params)
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
                [0 => 2, 1 => 12],
                $callable
            ];
            $tests[] = [
                'filter',
                [1, 2, 3, 12],
                [2, 12],
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

        // merge
        $tests[] = [
            'merge',
            ['a', 'b'],
            ['a', 'b', 'c', 'd', 'e'],
            ['c', 'd'],
            ['e'],
        ];
        $tests[] = [
            'merge',
            ['a', 'b', 'k1' => 'v1'],
            ['a', 'b', 'c',  'd', 'e', 'k1' => 'v1', 'k2' => 'v2'],
            ['c', 'k2' => 'v2', 'd'],
            ['e'],
        ];
        $tests[] = [
            'merge',
            [],
            [],
            [],
            [],
        ];

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

        // slice
        $tests[] = [
            'slice',
            ['a', 'b', 'c', 'd'],
            ['b', 'c'],
            1,
            2
        ];
        $tests[] = [
            'slice',
            ['a', 'b', 'c', 'd'],
            ['d'],
            -1,
        ];
        $tests[] = [
            'slice',
            ['a', 'b', 'c', 'd'],
            ['b'],
            -3,
            -2
        ];
        $tests[] = [
            'slice',
            ['a', 'b', 'c', 'd'],
            ['a', 'b'],
            0,
            -2
        ];
        // Preserve keys in associative arrays
        $tests[] = [
            'slice',
            ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4],
            ['b' => 2, 'c' => 3],
            1,
            2
        ];
        $tests[] = [
            'slice',
            [5 => 'a', 6 => 'b', 8 => 'c', 10 => 'd'],
            [6 => 'b', 8 => 'c'],
            1,
            2
        ];
        
        // splice
        $tests[] = [
            'splice',
            ['a', 'b', 'c', 'd'],
            ['a', 'q', 'x', 'd'],
            1,
            2,
            ['q', 'x']
        ];
        $tests[] = [
            'splice',
            ['a', 'b', 'c', 'd'],
            ['a', 'b', 'c', 'q'],
            -1,
            1,
            'q'
        ];
        $tests[] = [
            'splice',
            ['a', 'b', 'c', 'd'],
            ['a', 'q', 'd'],
            -3,
            2,
            'q'
        ];
        $tests[] = [
            'splice',
            ['a', 'b', 'c', 'd'],
            ['a', 'q', 'd'],
            -3,
            -1,
            'q'
        ];
        $tests[] = [
            'splice',
            ['a', 'b', 'c', 'd'],
            ['a', 'q', 'd'],
            1,
            2,
            'q'
        ];
        $tests[] = [
            'splice',
            ['a', 'b', 'c', 'd'],
            ['a', 'q', 'b', 'c', 'd'],
            1,
            0,
            'q'
        ];
        $tests[] = [
            'splice',
            ['a', 'b', 'c', 'd'],
            ['a', 'q'],
            1,
            null,
            'q'
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

        // unique
        $tests[] = [
            'unique',
            ['a', 'a', 'a', 'b', 'a'],
            [0 => 'a', 1 => 'b']
        ];
        $tests[] = [
            'unique',
            ['a', 'a', 'a', 'b', 'a'],
            ['a', 'b']
        ];
        $tests[] = [
            'unique',
            [3 => 'a', 4 => 'a', 6 => 'c'],
            [3 => 'a', 6 => 'c']
        ];
        $tests[] = [
            'unique',
            [['a' => 1], ['b' => 2], ['a' => 1]],
            [['a' => 1], ['b' => 2]],
        ];
        $tests[] = [
            'unique',
            ['a', 'a', 'a' . 'b', 'b', 'ab'],
            ['a', 'ab', 'b']
        ];

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

    public function testMakeArray()
    {
        $this->assertEquals([1, 2, 3], Ar::makeArray([1, 2, 3]));
        $this->assertEquals([1, 2, 3], Ar::makeArray(new MyIterable([1, 2, 3])));
        $this->assertIsArray(Ar::makeArray(new MyIterable([1, 2, 3])));
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

<?php

declare(strict_types=1);

namespace Frontwise\Ar\Test;

use Frontwise\Ar\Ar;
use Frontwise\Ar\ArFluent;
use PHPUnit\Framework\TestCase;

final class ReduceTest extends TestCase
{
    /** @dataProvider reduceProvider */
    public function testReduce(array $input, $initial, $expected, callable $callable)
    {
        // Functional
        $a = $input;
        $b = Ar::reduce($a, $callable, $initial);
        $this->assertEquals($expected, $b);

        // Iterable
        $it = new MyIterable($a);
        $b = Ar::reduce($a, $callable, $initial);
        $this->assertEquals($expected, $b);

        // Fluent
        $b = Ar::new($a)
            ->reduce($callable, $initial);
        $this->assertEquals($expected, $b);
    }

    public function reduceProvider()
    {
        $tests = [];

        $add = function ($carry, $value, $key) {
            return $value + $carry;
        };
        $tests[] = [
            [1, 2, 4], // input
            0, // initial
            7, // expected
            $add // callable
        ];
        $tests[] = [
            [1, 2, 4], // input
            null, // initial
            7, // expected
            $add // callable
        ];
        $tests[] = [
            [1, 2, 4], // input
            3, // initial
            10, // expected
            $add // callable
        ];
        $tests[] = [
            ['a' => 1, 2, 'b' => 4], // input
            0, // initial
            7, // expected
            $add // callable
        ];

        $addKeys = function ($carry, $value, $key) {
            return $key + $carry;
        };
        $tests[] = [
            [1, 2, 4], // input
            4, // initial
            7, // expected
            $addKeys // callable
        ];

        return $tests;
    }
}

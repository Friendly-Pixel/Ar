<?php

declare(strict_types=1);

namespace FriendlyPixel\Ar\Test;

use FriendlyPixel\Ar\Ar;
use FriendlyPixel\Ar\ArFluent;
use FriendlyPixel\Ar\Test\Traits\BaseTrait;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;

final class ExceptionsTest extends TestCase
{
    use BaseTrait;

    /** @dataProvider provider */
    public function testException($funcName, $param0, $param1)
    {
        $this->expectException(TypeError::class);
        Ar::$funcName($param0, $param1);
    }

    function provider()
    {
        $tests = [];
        $func = function () {
        };

        $tests[] = ['wrap', new stdClass(), null];
        $tests[] = ['makeArray', new stdClass(), null];
        foreach ([null, new stdClass()] as $empty) {
            $tests[] = ['count', $empty, $func];
            $tests[] = ['filter', $empty, $func];
            $tests[] = ['flat', $empty, 1];
            $tests[] = ['forEach', $empty, $func];
            $tests[] = ['implode', $empty, ','];
            $tests[] = ['makeArray', $empty, $func];
            $tests[] = ['map', $empty, $func];
            $tests[] = ['mapKeys', $empty, $func];
            $tests[] = ['reduce', $empty, $func];
            $tests[] = ['search', $empty, $func];
            $tests[] = ['sort', $empty, $func];
        }
        return $tests;
    }

    public function testNew()
    {
        $this->expectException(TypeError::class);
        Ar::wrap(new stdClass());
    }
}

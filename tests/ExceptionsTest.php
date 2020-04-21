<?php

declare(strict_types=1);

namespace Frontwise\Ar\Test;

use Frontwise\Ar\Ar;
use Frontwise\Ar\ArFluent;
use Frontwise\Ar\Test\Traits\BaseTrait;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ExceptionsTest extends TestCase
{
    use BaseTrait;

    /** @dataProvider provider */
    public function testException($funcName, $param0, $param1)
    {
        $this->expectException(InvalidArgumentException::class);
        Ar::$funcName($param0, $param1);
    }

    function provider()
    {
        $tests = [];
        $func = function () {
        };

        $tests[] = ['new', new stdClass(), null];
        foreach ([null, new stdClass()] as $empty) {
            $tests[] = ['count', $empty, $func];
            $tests[] = ['filter', $empty, $func];
            $tests[] = ['filterValues', $empty, $func];
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
        $this->expectException(InvalidArgumentException::class);
        Ar::new(new stdClass());
    }
}

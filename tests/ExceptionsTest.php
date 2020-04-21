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
        $tests[] = ['filter', null, $func];
        $tests[] = ['flat', null, 1];
        $tests[] = ['forEach', null, $func];
        $tests[] = ['implode', null, ','];
        $tests[] = ['map', null, $func];
        $tests[] = ['mapKeys', null, $func];
        $tests[] = ['reduce', null, $func];
        $tests[] = ['search', null, $func];
        $tests[] = ['sort', null, $func];
        $tests[] = ['makeArray', null, $func];
        return $tests;
    }

    public function testNew()
    {
        $this->expectException(InvalidArgumentException::class);
        Ar::new(new stdClass());
    }
}

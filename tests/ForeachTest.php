<?php

declare(strict_types=1);

namespace Frontwise\Ar\Test;

use Frontwise\Ar\Ar;
use Frontwise\Ar\ArFluent;
use PHPUnit\Framework\TestCase;

final class ForeachTest extends TestCase
{

    public function testForeach()
    {
        $array = [4, 5, 6];
        $validate = function ($value, $key) use ($array, &$i) {
            $this->assertEquals($array[$key], $value);
            $i++;
        };

        $i = 0;
        Ar::forEach($array, $validate);
        $this->assertEquals(count($array), $i);

        $i = 0;
        Ar::new($array)->forEach($validate);
        $this->assertEquals(count($array), $i);
    }
}

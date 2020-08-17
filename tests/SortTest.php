<?php

declare(strict_types=1);

namespace FriendlyPixel\Ar\Test;

use FriendlyPixel\Ar\Ar;
use FriendlyPixel\Ar\ArFluent;
use PHPUnit\Framework\TestCase;

final class SortTest extends TestCase
{

    public function testSortDoesntModify()
    {
        $array = [3, 2, 8];
        $result = Ar::sort($array, [$this, 'sortIncreasing']);
        $this->assertEquals($array, [3, 2, 8]);
        $this->assertEquals($result, [2, 3, 8]);
        $result = Ar::wrap($array)->sort([$this, 'sortIncreasing'])->toArray();
        $this->assertEquals($array, [3, 2, 8]);
        $this->assertEquals($result, [2, 3, 8]);
    }

    public function sortIncreasing($a, $b)
    {
        return $a - $b;
    }
}

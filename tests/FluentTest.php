<?php

declare(strict_types=1);

namespace FriendlyPixel\Ar\Test;

use FriendlyPixel\Ar\Ar;
use FriendlyPixel\Ar\ArFluent;
use FriendlyPixel\Ar\Test\Traits\BaseTrait;
use PHPUnit\Framework\TestCase;

final class FluentTest extends TestCase
{
    use BaseTrait;

    public function testTraversable(): void
    {
        $expected = [2, 4, 6];

        $a = Ar::wrap([1, 2, 3])->map([$this, 'timesTwo']);
        foreach ($a as $key => $value) {
            $this->assertEquals($value, $expected[$key]);
        }

        foreach (Ar::wrap([1, 2, 3])->map([$this, 'timesTwo']) as $key => $value) {
            $this->assertEquals($value, $expected[$key]);
        }
    }

    public function testFluentEquals()
    {
        $numbers1 = (new ArFluent([1, 2, 3]))
            ->map(function ($value, $key) {
                return $value * 2;
            });

        $numbers2 = Ar::wrap([1, 2, 3])
            ->map(function ($value, $key) {
                return $value * 2;
            });

        $this->assertEquals($numbers1, $numbers2);
    }

    public function testFluentArrayAccess()
    {
        $fluent = Ar::wrap(['a' => 1, 'b' => 15]);
        $this->assertEquals(1, $fluent['a']);
        $this->assertEquals(15, $fluent['b']);

        $fluent2 = $fluent->map([$this, 'timesTwo']);
        $this->assertEquals(2, $fluent2['a']);
        $this->assertEquals(30, $fluent2['b']);

        $fluent2['c'] = 81;
        $this->assertEquals(81, $fluent2['c']);


        $this->assertEquals(false, isset($fluent2['f']));
        $this->assertEquals(true, isset($fluent2['c']));
    }

    public function timesTwo($value)
    {
        return $value * 2;
    }

    public function testOffsetSet()
    {
        $a = Ar::wrap([1, 2, 3]);
        $a[1] = 5;
        $a[] = 10;
        $this->assertEquals($a->unwrap(), [1, 5, 3, 10]);
    }

    public function testOffsetUnset()
    {
        $a = Ar::wrap(['a', 'b', 'c']);
        unset($a[1]);
        $this->assertEquals($a->unwrap(), [0 => 'a', 2 => 'c']);
    }

    public function testJsonSerializable()
    {
        $array = ['a', 'b', 'c'];
        $a = Ar::wrap($array);
        $this->assertEquals(json_encode($array), json_encode($a));
    }
}

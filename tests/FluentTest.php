<?php

declare(strict_types=1);

namespace Frontwise\Ar\Test;

use Frontwise\Ar\Ar;
use Frontwise\Ar\ArFluent;
use PHPUnit\Framework\TestCase;

final class FluentTest extends TestCase
{
    public function testTraversable(): void
    {
        $expected = [2, 4, 6];

        $a = Ar::new([1, 2, 3])->map([$this, 'timesTwo']);
        foreach ($a as $key => $value) {
            $this->assertEquals($value, $expected[$key]);
        }

        foreach (Ar::new([1, 2, 3])->map([$this, 'timesTwo']) as $key => $value) {
            $this->assertEquals($value, $expected[$key]);
        }
    }

    public function testFluentEquals()
    {
        $numbers1 = (new ArFluent([1, 2, 3]))
            ->map(function ($value, $key) {
                return $value * 2;
            });

        $numbers2 = Ar::new([1, 2, 3])
            ->map(function ($value, $key) {
                return $value * 2;
            });

        $this->assertEquals($numbers1, $numbers2);
    }

    public function testFluentArrayAccess()
    {
        $fluent = Ar::new(['a' => 1, 'b' => 15]);
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
}

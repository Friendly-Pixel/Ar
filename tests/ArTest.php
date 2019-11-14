<?php
declare(strict_types=1);

namespace Frontwise\Ar\Test;

use Frontwise\Ar\Ar;
use Frontwise\Ar\ArFluent;
use PHPUnit\Framework\TestCase;

final class ArTest extends TestCase
{
    /** @dataProvider returnsArrayProvider */
    public function testReturnsArrayFunc(string $funcName, array $input, array $expected, callable $callable) {
        // Functional
        $a = $input;
        $b = Ar::$funcName($a, $callable);
        $this->assertEquals($expected, $b);
        
        // Iterable
        $it = new MyIterable($a);
        $b = Ar::$funcName($a, $callable);
        $this->assertEquals($expected, $b);
        
        // Fluent
        $b = Ar::new($a)
            ->$funcName($callable)
            ->unwrap()
        ;
        $this->assertEquals($expected, $b);
    }
    
    /** @dataProvider returnsValueProvider */
    public function testReturnsValueFunc(string $funcName, array $input, $expected, callable $callable) {
        // Functional
        $a = $input;
        $b = Ar::$funcName($a, $callable);
        $this->assertEquals($expected, $b);
        
        // Iterable
        $it = new MyIterable($a);
        $b = Ar::$funcName($a, $callable);
        $this->assertEquals($expected, $b);
        
        // Fluent
        $b = Ar::new($a)
            ->$funcName($callable)
        ;
        $this->assertEquals($expected, $b);
    }
    
    public function returnsArrayProvider() {
        $result = [];
        
        // map
        foreach([
            function ($v) { return $v + $v; },
            [$this, 'timesTwo'],
        ] as $callable) {
            $result[] = ['map', [1, 2, 3], [2, 4, 6], $callable];
            $result[] = ['map', ['a' => 1, 'b' => 2, 'c' => 3], ['a' => 2, 'b' => 4, 'c' => 6], $callable];
            $result[] = ['map', [12 => 1, 81 => 2, 13 => 3], [12 => 2, 81 => 4, 13 => 6], $callable];
        }
        
        // mapKeys
        foreach([
            function ($v, $k) { return $k * 2; },
            [$this, 'keyTimesTwo'],
        ] as $callable) {
            $result[] = ['mapKeys', [1, 2, 3], [0 => 1, 2 => 2, 4 => 3], $callable];
            $result[] = ['mapKeys', [12 => 1,  81 => 2, 13 => 3], [24 => 1, 162 => 2, 26 => 3], $callable];
        }
        
        // filter
        foreach([
            function ($v) { return $v % 2 == 0; },
            [$this, 'isEven'],
        ] as $callable) {
            $result[] = [
                'filter',
                [1, 2, 3, 12],
                [1 => 2, 3 => 12],
                $callable
            ];
            $result[] = [
                'filter',
                ['a' => 1,  'b' => 2, 'c' => 3],
                ['b' => 2],
                $callable
            ];
            $result[] = [
                'filter',
                [12 => 1,  81 => 2, 13 => 3],
                [81 => 2],
                $callable
            ];
        }
        
        
        return $result;
    }
    
        public function returnsValueProvider() {
            $result = [];

            // search
            $target = ['a' => 2, 'c' => 3];
            foreach([
                function ($v) { return ($v['a'] ?? 0) == 2; },
            ] as $callable) {
                $result[] = [
                    'search',
                    [[], ['a' => 1], $target],
                    $target,
                    $callable
                ];
                $result[] = [
                    'search',
                    [[], ['a' => 1], ['b' => 8]],
                    null,
                    $callable
                ];
            }

            return $result;
        }
    
    
    public function testTraversable(): void
    {
        $expected = [2, 4, 6];
        
        $a = Ar::new([1, 2, 3])->map([$this, 'timesTwo']);
        foreach($a as $key => $value) {
            $this->assertEquals($value, $expected[$key]);
        }
        
        foreach(Ar::new([1, 2, 3])->map([$this, 'timesTwo']) as $key => $value) {
            $this->assertEquals($value, $expected[$key]);
        }
    }
    
    public function testFluentEquals()
    {
        $numbers1 = (new ArFluent([1, 2, 3]))
            ->map(function ($value, $key) { return $value * 2; })
        ;
        
        $numbers2 = Ar::new([1, 2, 3])
            ->map(function ($value, $key) { return $value * 2; })
        ;
        
        $this->assertEquals($numbers1, $numbers2);
    }
    
    public function testFluentArrayAccess() {
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
    
    public function testFluentImplode() {
        $this->assertEquals(Ar::new([1, 2, 3])->implode(' - '), '1 - 2 - 3');
        $this->assertEquals(Ar::new(['a', 22])->implode(','), 'a,22');
        $this->assertEquals(Ar::new([2, 3, 4])->implode(), '234');
    }
    
    public function timesTwo($value) {
        return $value * 2;
    }
    public function keyTimesTwo($value, $key) {
        return $key * 2;
    }
    
    public function isEven($value) {
        return $value % 2 == 0;
    }
}

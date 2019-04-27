<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Frontwise\Ar;
use Frontwise\ArFluent;

final class ArTest extends TestCase
{
    public function testMap(): void
    {
        $expected = [2, 4, 6];
        
        // Functional
        $a = [1, 2, 3];
        $b = Ar::map($a, function ($v) { return $v + $v; });
        $this->assertEquals($expected, $b);
        
        // Fluent
        $b = Ar::new($a)
            ->map(function ($v) { return $v + $v; })
            ->unwrap()
        ;
        $this->assertEquals($expected, $b);
        
        // Callable
        $b = Ar::new($a)
            ->map([$this, 'timesTwo'])
            ->unwrap()
        ;
        $this->assertEquals($expected, $b);
        
        // String keys
        $a = ['a' => 1,  'b' => 2, 'c' => 3];
        $b = Ar::map($a, function ($v) { return $v + $v; });
        $this->assertEquals(['a' => 2,  'b' => 4, 'c' => 6], $b);
        
        // non-following int keys
        $a = [12 => 1,  81 => 2, 13 => 3];
        $b = Ar::map($a, [$this, 'timesTwo']);
        $this->assertEquals([12 => 2,  81 => 4, 13 => 6], $b);
    }
    
    public function testMapKeys(): void
    {
        $a = [1, 2, 3];
        $b = Ar::mapKeys($a, function ($value, $key) { return $key * 2; });
        $this->assertEquals([0 => 1, 2 => 2, 4 => 3], $b);
    }
    
    public function testFilter(): void
    {
        $expected = [1 => 2, 3 => 12];
        
        // Functional
        $a = [1, 2, 3, 12];
        $b = Ar::filter($a, function ($v) { return $v % 2 == 0; });
        $this->assertEquals($expected, $b);
        
        // Fluent
        $b = Ar::new($a)
            ->filter(function ($v) { return $v % 2 == 0; })
            ->unwrap()
        ;
        $this->assertEquals($expected, $b);
        
        // Callable
        $b = Ar::new($a)
            ->filter([$this, 'even'])
            ->unwrap()
        ;
        $this->assertEquals($expected, $b);
        
        // String keys
        $a = ['a' => 1,  'b' => 2, 'c' => 3];
        $b = Ar::filter($a, [$this, 'even']);
        $this->assertEquals(['b' => 2], $b);
        
        // non-following int keys
        $a = [12 => 1,  81 => 2, 13 => 3];
        $b = Ar::filter($a, [$this, 'even']);
        $this->assertEquals([81 => 2], $b);
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
    
    public function testFluent()
    {
        $numbers1 = (new ArFluent([1, 2, 3]))
            ->map(function ($value, $key) { return $value * 2; })
        ;
        
        $numbers2 = Ar::new([1, 2, 3])
            ->map(function ($value, $key) { return $value * 2; })
        ;
        
        $this->assertEquals($numbers1, $numbers2);
    }
    
    public function timesTwo($input) {
        return $input + $input;
    }
    
    public function even($input) {
        return $input % 2 == 0;
    }
}

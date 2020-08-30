<?php

namespace App\Tests\Helper;

use App\Helper\ArrayHelper;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{

    public function testRecursiveImplodeReturnsValidResult()
    {
        $array = ['test' => ['test2' => 3, 1], 'toto' => 51];
        $result = ArrayHelper::recursiveImplode($array);
        $this->assertEquals('3,1,51', $result);
    }

    public function testRecursiveImplodeWithGlueReturnsValidResult()
    {
        $array = ['test' => ['test2' => 3, 1], 'toto' => 51];
        $result = ArrayHelper::recursiveImplode($array, ' + ');
        $this->assertEquals('3 + 1 + 51', $result);
    }

    public function testRecursiveImplodeWithIncludeKeysReturnsValidResult()
    {
        $array = ['test' => ['test2' => 3, 1], 'toto' => 51];
        $result = ArrayHelper::recursiveImplode($array, ',', true);
        $this->assertEquals('test2 : 3,0 : 1,toto : 51', $result);
    }

    public function testDotReturnsValidResult()
    {
        $array = ['test' => ['test2' => 3, 1], 'toto' => 51];
        $result = ArrayHelper::dot($array);
        $this->assertEquals(['test.test2' => 3, 'test.0' => 1, 'toto' => 51], $result);
    }
}

<?php
use PHPUnit\Framework\TestCase;

use Merophp\Framework\Utility\ArrayUtility;

/**
 * @covers \Merophp\Framework\Utility\ArrayUtility
 */
final class ArrayUtilityTest extends TestCase
{

    public function testFind()
    {
        $index = ArrayUtility::find(['a','b','c'], function($value, $key){
            return $value == 'c' && $key == 2;
        });
        $this->assertEquals(2, $index);

        $index = ArrayUtility::find(['a','b','c'], function($value, $key){
            return $value == 'd';
        });
        $this->assertFalse($index);
    }

    public function testFindWithInvalidArrayInput()
    {
        $this->expectException(InvalidArgumentException::class);

        $index = ArrayUtility::find('wrong input', function($value, $key){
            return $value == 'c';
        });
    }

    public function testFindWithInvalidCallbackInput()
    {
        $this->expectException(InvalidArgumentException::class);

        $index = ArrayUtility::find(['a','b','c'],'wrong input');
    }
}

<?php

use PHPUnit\Framework\TestCase;

use Merophp\Framework\Cache\CookieCache;
use Merophp\Framework\Cache\Exception\InvalidArgumentException;
use Merophp\ObjectManager\ObjectManager;

/**
 * @covers \Merophp\Framework\Cache\CookieCache
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
final class CookieCacheTest extends TestCase{
    /**
     * @var CookieCache
     */
    protected static $cacheInstance;


    public static function setUpBeforeClass():void{
        self::$cacheInstance = ObjectManager::get(CookieCache::class);
    }

    public function testSetWithInvalidInput(){
        $this->expectException(InvalidArgumentException::class);
        self::$cacheInstance->set(null, 'test');
    }

    public function testSetMultipleWithInvalidInput(){
        $this->expectException(InvalidArgumentException::class);
        self::$cacheInstance->setMultiple(null);
    }

    public function testGetMultipleWithInvalidInput(){
        $this->expectException(InvalidArgumentException::class);
        self::$cacheInstance->getMultiple(null);
    }

    public function testGetWithInvalidInput(){
        $this->expectException(InvalidArgumentException::class);
        self::$cacheInstance->get(null);
    }

    public function testHasWithInvalidInput(){
        $this->expectException(InvalidArgumentException::class);
        self::$cacheInstance->has(null);
    }

    public function testDeleteWithInvalidInput(){
        $this->expectException(InvalidArgumentException::class);
        self::$cacheInstance->delete(null);
    }

    public function testDeleteMultipleWithInvalidInput(){
        $this->expectException(InvalidArgumentException::class);
        self::$cacheInstance->delete(null);
    }

    public function testSet(){
        $this->assertTrue(self::$cacheInstance->set('key1', 'value1'));
    }

    public function testGet(){
        self::$cacheInstance->set('key1', 'value1');
        $this->assertEquals('value1', self::$cacheInstance->get('key1'));
        $this->assertEquals('fallbackReturn', self::$cacheInstance->get('key2', 'fallbackReturn'));
    }

    public function testMultipleSet(){
        $this->assertTrue(self::$cacheInstance->setMultiple(['key1' => 'value1','key2' => 'value2']));
    }

    public function testMultipleGet(){
        self::$cacheInstance->setMultiple(['key1' => 'value1','key2' => 'value2']);

        $this->assertArrayHasKey('key1', self::$cacheInstance->getMultiple(['key1']));
        $this->assertArrayNotHasKey('key2', self::$cacheInstance->getMultiple(['key1']));
    }

    public function testHas(){
        self::$cacheInstance->set('key1', 'value1');
        $this->assertTrue(self::$cacheInstance->has('key1'));
        $this->assertFalse(self::$cacheInstance->has('x'));
    }

    public function testDelete(){
        self::$cacheInstance->set('key1', 'value1');
        self::$cacheInstance->delete('key1');
        $this->assertFalse(self::$cacheInstance->has('key1'));
    }

    public function testDeleteMultiple(){
        self::$cacheInstance->setMultiple(['key1' => 'value1','key2' => 'value2','key3' => 'value3']);
        self::$cacheInstance->deleteMultiple(['key1', 'key2']);
        $this->assertFalse(self::$cacheInstance->has('key1'));
        $this->assertFalse(self::$cacheInstance->has('key2'));
        $this->assertTrue(self::$cacheInstance->has('key3'));
    }

    public function testClear(){
        self::$cacheInstance->setMultiple(['key1' => 'value1','key2' => 'value2','key3' => 'value3']);
        self::$cacheInstance->clear();
        $this->assertFalse(self::$cacheInstance->has('key1'));
        $this->assertFalse(self::$cacheInstance->has('key2'));
        $this->assertFalse(self::$cacheInstance->has('key3'));
    }

}

<?php

use Merophp\ObjectManager\ObjectManager;
use PHPUnit\Framework\TestCase;
use Merophp\Framework\Autoloader\Autoloader;

/**
 * @covers \Merophp\Framework\Autoloader\Autoloader
 */
class AutoloaderTest extends TestCase
{
    /**
     * @var Autoloader
     */
    protected static $autoloaderInstance;

    public static function setUpBeforeClass(): void
    {
        self::$autoloaderInstance = ObjectManager::get(Autoloader::class);
    }

    public function testAutoloadClass()
    {
        self::$autoloaderInstance->registerNamespacePrefix('Vendor1', __DIR__.'/testData');

        $this->assertTrue(class_exists(Vendor1\Test\TestClass::class));
    }
}

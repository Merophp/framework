<?php
use PHPUnit\Framework\TestCase;
use Merophp\ObjectManager\ObjectManager;
use Merophp\Framework\EventManagement\EventManager;

require_once __DIR__.'/testData/TestEvent.php';

/**
 * @covers \Merophp\Framework\EventManagement\EventManager
 */
class EventManagerTest extends TestCase
{
    /**
     * @var EventManager
     */
    protected static $eventManagerInstance;

    public static function setUpBeforeClass(): void
    {
        self::$eventManagerInstance = ObjectManager::get(EventManager::class);
    }

    public function testEventDispatching()
    {
        self::$eventManagerInstance->addEventListeners(function(TestEvent $event){echo 'Foo';echo $event->getBar();});

        ob_start();
        self::$eventManagerInstance->dispatch(new TestEvent('Bar'));
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('FooBar', $result);
    }
}

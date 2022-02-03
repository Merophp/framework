<?php
use PHPUnit\Framework\TestCase;
use Merophp\ObjectManager\ObjectManager;
use Merophp\Framework\App;
use Merophp\Framework\AppFactory;

/**
 * @covers \Merophp\Framework\App
 */
class AppFactoryTest extends TestCase
{
    public function test()
    {
        $appInstance = AppFactory::create();
        $this->assertNotEmpty($appInstance->getEventManager());
        $this->assertNotEmpty($appInstance->getViewEngine());
        $this->assertNotEmpty($appInstance->getRouter());
        $this->assertNotEmpty($appInstance->getBundleManager());
        $this->assertNotEmpty($appInstance->getRequest());
        $this->assertNotEmpty($appInstance->getRequestHandler());
        $this->assertNotEmpty($appInstance->getResponseProcessor());
        $this->assertNotEmpty($appInstance->getLogManager());
    }
}

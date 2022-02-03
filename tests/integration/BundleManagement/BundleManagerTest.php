<?php

use PHPUnit\Framework\TestCase;

use Merophp\Framework\BundleManagement\BundleBootstrapper\AbstractBundleBootstrapper;
use Merophp\Framework\BundleManagement\BundleManager;
use Merophp\ObjectManager\ObjectManager;


/**
 * @covers \Merophp\Framework\BundleManagement\BundleManager
 */
final class BundleManagerTest extends TestCase
{
    /**
     * @var BundleManager
     */
    protected static $bundleManagerInstance;

    public static function setUpBeforeClass(): void
    {
        self::$bundleManagerInstance = ObjectManager::get(BundleManager::class);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testRegisterBundle()
    {
        self::$bundleManagerInstance->registerBundle('CompanyA\\BundleA', __DIR__.'/testData/BundleA');
        self::$bundleManagerInstance->registerBundle('CompanyA\\BundleB', __DIR__.'/testData/BundleB');
    }

    /**
     *
     */
    public function testStartRegisteredBundles()
    {
        ob_start();
        self::$bundleManagerInstance->startRegisteredBundles();
        $var = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('SetupASetupBSetupC', $var);
    }

    /**
     *
     */
    public function testTearDownStartedBundles()
    {
        ob_start();
        self::$bundleManagerInstance->tearDownStartedBundles();
        $var = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('TearDownATearDownBTearDownC', $var);
    }
}

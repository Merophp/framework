<?php

use Merophp\TextViewPlugin\TextView;
use Merophp\ViewEngine\ViewPlugin\ViewPlugin;
use PHPUnit\Framework\TestCase;
use Merophp\ObjectManager\ObjectManager;
use Merophp\Framework\ViewEngine\ViewEngine;

/**
 * @covers \Merophp\Framework\ViewEngine\ViewEngine
 */
class ViewEngineTest extends TestCase
{
    /**
     * @var ViewEngine
     */
    protected static $viewEngineInstance;

    public static function setUpBeforeClass(): void
    {
        self::$viewEngineInstance = ObjectManager::get(ViewEngine::class);
    }

    public function test()
    {
        self::$viewEngineInstance->registerViewPlugins(new ViewPlugin(TextView::class));
        $testView = self::$viewEngineInstance->initializeView();
        $testView->text('Foo');
        $this->assertEquals('Foo', self::$viewEngineInstance->renderView($testView));

        $testView = self::$viewEngineInstance->initializeView('Text');
        $testView->text('Bar');
        $this->assertEquals('Bar', self::$viewEngineInstance->renderView($testView));
    }
}

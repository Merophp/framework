<?php

use Merophp\Framework\AppFactory;
use Merophp\Framework\Http\ResponseProcessor;
use Merophp\TextViewPlugin\TextView;
use Merophp\ViewEngine\ViewPlugin\ViewPlugin;
use PHPUnit\Framework\TestCase;
use Shieldon\Psr7\ServerRequest;

class HoleRequestProcessingTest extends TestCase
{
    public function test()
    {
        $app = AppFactory::create();
        $app->registerBundle('OrgaA\\BundleA', __DIR__.'/testData/BundleA');
        $app->getViewEngine()->registerViewPlugins(
            new ViewPlugin(TextView::class)
        );
        $app->getResponseProcessor()->setOutputMode(
            ResponseProcessor::OUTPUT_MODE_PRINT
        );
        $app->setRequest(new ServerRequest(
            'POST',
            '/test/Free/foo/Willy'
        ));

        $oldErrorLevel = error_reporting();
        error_reporting(E_ERROR);
        ob_start();

        $app->start();
        $app->tearDown();
        $var = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('Free Willy now', $var);
        error_reporting($oldErrorLevel);
    }
}

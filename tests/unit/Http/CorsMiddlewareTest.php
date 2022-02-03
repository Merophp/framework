<?php

use Merophp\Framework\Http\Cors\CorsConfiguration;
use Merophp\Framework\Http\Cors\CorsMiddleware;
use Merophp\Framework\Http\RequestHandler;
use Merophp\Framework\Http\Factory\ServerRequestFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Merophp\Framework\Http\Cors\CorsMiddleware
 */
class CorsMiddlewareTest extends TestCase
{

    public function testProcess()
    {
        $config = new CorsConfiguration();
        $config->setAllowedOrigins(['localhost:8080']);
        $corsMiddlewareInstance = new CorsMiddleware($config);

        $testServerRequest = ServerRequestFactory::fromNew();
        $testServerRequest = $testServerRequest->withHeader('Origin', 'localhost:8080');
        $testRequestHandler = new RequestHandler();
        $response = $corsMiddlewareInstance->process($testServerRequest, $testRequestHandler);
        $this->assertEquals('localhost:8080', $response->getHeaderLine('Access-Control-Allow-Origin'));
    }

    public function testProcessWithDisallowedOrigin()
    {
        $config = new CorsConfiguration();
        $config->setAllowedOrigins(['localhost:8080']);
        $corsMiddlewareInstance = new CorsMiddleware($config);
        $testRequestHandler = new RequestHandler();

        $testServerRequest = ServerRequestFactory::fromNew()->withHeader('Origin', 'localhost:8085');
        $response = $corsMiddlewareInstance->process($testServerRequest, $testRequestHandler);
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testProcessWithAllOriginsAllowed()
    {
        $config = new CorsConfiguration();
        $config->setAllowedOrigins(['*']);
        $corsMiddlewareInstance = new CorsMiddleware($config);
        $testRequestHandler = new RequestHandler();

        $testServerRequest = ServerRequestFactory::fromNew()->withHeader('Origin', 'localhost:8085');
        $response = $corsMiddlewareInstance->process($testServerRequest, $testRequestHandler);
        $this->assertEquals('localhost:8085', $response->getHeaderLine('Access-Control-Allow-Origin'));
    }
}

<?php

use PHPUnit\Framework\TestCase;

use Shieldon\Psr7\Response;
use Shieldon\Psr7\ServerRequest;
use Merophp\ObjectManager\ObjectManager;
use Merophp\Framework\Routing\Router;

use Merophp\Router\{
    Routes\GetRoute,
    Routes\PostRoute,
    Routes\Scope,
    Exception\InvalidArgumentException,
    Exception\RoutingException
};

/**
 * @covers \Merophp\Framework\Routing\Router
 */
final class RouterTest extends TestCase
{
    /**
     * @var Router
     */
    protected static $routerInstance;


    public static function setUpBeforeClass(): void
    {
        self::$routerInstance = ObjectManager::get(Router::class);
    }

    public function testScopeWithInvalidInput()
    {
        $this->expectException(InvalidArgumentException::class);
        self::$routerInstance->scope(
            '/api',
            [
                new GetRoute('/test', function(){}),
                null
            ]
        );
    }

    public function testMatch()
    {
        self::$routerInstance->addRoutes(
            new GetRoute('/test2', function(){}),
            new GetRoute('/test2/*', function(){}),
            new PostRoute('/test2', function(){}),
            new GetRoute('*', function(){}),
        );
        $request = new ServerRequest(
            'GET',
            '/test2'
        );

        $route = self::$routerInstance->match($request);
        $this->assertEquals('/test2', $route->getPattern());
        $this->assertEquals('GET', $route->getMethods()[0]);


        $request = new ServerRequest(
            'GET',
            '/test2/hallo'
        );
        $route = self::$routerInstance->match($request);
        $this->assertEquals('/test2/*', $route->getPattern());
    }

    public function testDispatch()
    {
        $route = new GetRoute('/test2',
            function($request, $response){
                $response->getBody()->write('function');
                return $response;
            }
        );

        $request = new ServerRequest(
            'GET',
            '/test2'
        );
        $response = self::$routerInstance->dispatch($route, $request, new Response());
        $response->getBody()->rewind();
        $this->assertEquals('function', $response->getBody()->getContents());

        $this->expectException(RoutingException::class);
        $request = new ServerRequest(
            'GET',
            '/test2'
        );
        self::$routerInstance->dispatch(new GetRoute('/foo/bar', function(){}), $request, new Response());
    }
}

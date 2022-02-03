<?php
declare(strict_types=1);

namespace Merophp\Framework\Routing;

use ReflectionMethod;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Merophp\Router\Routes\RouteInterface;
use Merophp\Router\Routes\Scope;
use Merophp\Router\Exception\InvalidArgumentException;
use Merophp\Router\Collection\RouteCollection;
use Merophp\Router\Provider\RouteProvider;
use Merophp\Router\Exception\RoutingException;

use Merophp\Framework\RequestControlling\ControllerInterface;
use Merophp\Framework\RequestControlling\ControllerDecorator;

use Merophp\ObjectManager\ObjectManager;
use Merophp\Singleton\Singleton;

use Merophp\Framework\EventManagement\EventManagerAwareInterface;
use Merophp\Framework\EventManagement\EventManagerTrait;

use Merophp\Framework\Logging\LogManagerAwareInterface;
use Merophp\Framework\Logging\LogManagerTrait;

use Merophp\Framework\Routing\Exception\InvalidHandlerReturn;

use Merophp\Router\Router as RealRouter;

/**
 * Router facade for the real router from merophp/router
 *
 * @author Robert Becker
 */
final class Router extends Singleton implements EventManagerAwareInterface, LogManagerAwareInterface
{
    use EventManagerTrait;
    use LogManagerTrait;

    /**
     * @var RealRouter 
     */
    protected RealRouter $realRouter;

    /**
     * @var RouteCollection 
     */
    protected RouteCollection $routeCollection;

    public function __construct()
    {
        $this->routeCollection = new RouteCollection;
        $this->realRouter = new RealRouter(new RouteProvider($this->routeCollection));
    }

	/**
	 * Add n routes to the route collection.
     * @api
     * @param array<RouteInterface> $routes
	 */
	public function addRoutes(RouteInterface ...$routes)
    {
        $this->routeCollection->addMultiple($routes);
	}

    /**
     * Add routes inside a scope.
     *
     * @param string $pattern
     * @param array $entries
     * @throws InvalidArgumentException
     * @api
     */
	public function scope(string $pattern, array $entries)
    {
		$scope = new Scope(
			$pattern,
			$entries
		);
        $this->routeCollection->addFromScope($scope);
	}

    /**
     * Finds a fitting route for the given request.
     *
     * @param ServerRequestInterface $request
     * @return RouteInterface
     * @throws RoutingException If no route fits.
     */
    public function match(ServerRequestInterface $request): RouteInterface{
        $route = $this->realRouter->match(
            $request->getMethod(),
            $request->getUri()->getPath()
        );
        $this->logManager->getLogger('framework')->debug(sprintf("Route matched [%s].",(string) $route));
        return $route;
    }

    /**
     * Executes the route handler.
     *
     * @param RouteInterface $route
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface The response object from the callable.
     * @throws InvalidHandlerReturn If the return value of the route handler is not a response object
     * @throws InvalidArgumentException If route handler is not callable.
     */
    public function dispatch(RouteInterface $route, ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $route->setHandler($this->prepareRouteHandler($route->getHandler()));

        $response = $this->realRouter->dispatch($route, [$request, $response]);

        if(!$response instanceof ResponseInterface)
            throw new InvalidHandlerReturn(sprintf(
                'Invalid handler return. Expected instance of %s. ',
                ResponseInterface::class
            ));

        return $response;
    }

    /**
     * Prepares a route handler. If $rawHandler is an array with class and method and the method is non-static, then
     * an instance of the class will be created. If the class also implements
     * Merophp\Framework\RequestControlling\ControllerInterface, then the object will be wrapped by the controller decorator.
     *
     * @throws InvalidArgumentException If $rawHandler is not callable.
     */
    protected function prepareRouteHandler($rawHandler): callable
    {
        $callable = $rawHandler;

		if(is_array($rawHandler)){
            if(method_exists($rawHandler[0], $rawHandler[1]))
                $methodReflection = new ReflectionMethod($rawHandler[0], $rawHandler[1]);

            if(!isset($methodReflection) || !$methodReflection->isStatic()){
                $object = ObjectManager::get($rawHandler[0]);

                if($object instanceof ControllerInterface)
                    $object = new ControllerDecorator($object);

                $callable = [$object, $rawHandler[1]];
            }
        }

        if(!is_callable($callable, true))
            throw new InvalidArgumentException('Passed handler is not callable!');

        return $callable;
	}

    /**
     * Creates a 404 error response object.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function get404Response(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('404 - Page not found');
        return $response->withStatus(404);
    }
}

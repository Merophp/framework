<?php
namespace Merophp\Framework\Http;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Merophp\Router\Exception\RouteNotFoundException;

use Merophp\Framework\Routing\RouterAwareInterface;
use Merophp\Framework\Routing\RouterTrait;
use Merophp\Framework\Http\Factory\ResponseFactory;

/**
 * It's the final request handler, the final request handler, oohhooo.
 *
 * @author Joey T.
 */
class FinalRequestHandler implements RequestHandlerInterface, RouterAwareInterface
{
    use RouterTrait;

	/**
     * @inheritdoc
     */
	public function handle(ServerRequestInterface $request): ResponseInterface
	{
        $response = ResponseFactory::fromNew();
        try{
            $route = $this->router->match($request);
            return $this->router->dispatch($route, $request, $response);
        }
        catch(RouteNotFoundException $e){
            return $this->router->get404Response($request, $response);
        }
	}
}

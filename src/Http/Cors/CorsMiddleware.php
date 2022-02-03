<?php
namespace Merophp\Framework\Http\Cors;

use Merophp\Framework\Http\Factory\ResponseFactory;
use Merophp\Framework\Http\Factory\StreamFactory;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @author Robert Becker
 */
final class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Configuration object for CORS settings
     * @var CorsConfiguration
     */
    private CorsConfiguration $configuration;

    /**
     * @param CorsConfiguration $configuration
     */
    public function __construct(CorsConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

	/**
	 * @inheritDoc
	 * @see \Psr\Http\Server\MiddlewareInterface::process()
     */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $incomingOrigin = $request->hasHeader('Origin') ? $request->getHeaderLine('Origin') : NULL;

        if ($incomingOrigin !== null && !self::isOriginAllowed($incomingOrigin)){
            return $this->get403Response($request);
        }

        if($request->getMethod() === 'OPTIONS'){
            $response = $this->getResponseForOptionsRequest($request);
        }
        else{
            $response = $handler->handle($request);
        }

        if(!empty($incomingOrigin))
            $response =  $response->withHeader('Access-Control-Allow-Origin', $incomingOrigin);

        return $response;
	}

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
	protected function getResponseForOptionsRequest(ServerRequestInterface $request): ResponseInterface
    {
		$response = ResponseFactory::fromNew();

		if(!empty($this->configuration->getAllowedMethods()))
             $response =  $response->withHeader('Access-Control-Allow-Methods', $this->configuration->getAllowedMethods());

		if(!empty($this->configuration->getAllowedHeaders()))
             $response =  $response->withHeader('Access-Control-Allow-Headers', $this->configuration->getAllowedHeaders());

		if(!empty($this->configuration->getMaxAge()))
             $response =  $response->withHeader('Access-Control-Max-Age', $this->configuration->getMaxAge());

		return $response;
	}

	/**
	 * @param string $incomingOrigin The incoming origin
	 */
	private function isOriginAllowed(string $incomingOrigin): bool
    {
		foreach($this->configuration->getAllowedOrigins() as $allowedOrigin){
			if($allowedOrigin == '*') return true;

			$pattern = '/^' . preg_quote($allowedOrigin, '/') . '$/';
		    //$allow = preg_match($pattern, $incomingOrigin);
		    if (preg_match($pattern, $incomingOrigin)) return true;
		}
		return false;
	}

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
	private function get403Response(ServerRequestInterface $request): ResponseInterface
    {
        $response = ResponseFactory::fromNew()
            ->withStatus(403)
            ->withHeader('Content-Type','text/plain')
            ->withBody(StreamFactory::fromNew());

        $response->getBody()->write(
            "CSRF protection in ".$request->getMethod()." request: detected invalid origin header: ".$request->getHeaderLine('Origin')
        );

        return $response;
    }
}

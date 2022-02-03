<?php
namespace Merophp\Framework;

use Exception;

use Psr\Http\Message\ServerRequestInterface;

use Merophp\Singleton\Singleton;

use Merophp\Framework\Routing\Router;
use Merophp\Framework\Routing\RouterAwareInterface;
use Merophp\Framework\Routing\RouterTrait;

use Merophp\Framework\BundleManagement\BundleManager;

use Merophp\Framework\Http\ResponseProcessor;
use Merophp\Framework\Http\RequestHandler;

use Merophp\Framework\ViewEngine\ViewEngine;
use Merophp\Framework\ViewEngine\ViewEngineAwareInterface;
use Merophp\Framework\ViewEngine\ViewEngineTrait;

use Merophp\Framework\EventManagement\EventManager;
use Merophp\Framework\EventManagement\EventManagerAwareInterface;
use Merophp\Framework\EventManagement\EventManagerTrait;

use Merophp\Framework\Logging\LogManager;
use Merophp\Framework\Logging\LogManagerTrait;
use Merophp\Framework\Logging\LogManagerAwareInterface;

class App extends Singleton implements EventManagerAwareInterface, ViewEngineAwareInterface, RouterAwareInterface, LogManagerAwareInterface
{
    use EventManagerTrait;
    use ViewEngineTrait;
    use RouterTrait;
    use LogManagerTrait;

    /**
     * @var BundleManager
     */
    private BundleManager $bundleManager;

    /**
     * @var RequestHandler
     */
    private RequestHandler $requestHandler;

    /**
     * @var ResponseProcessor
     */
    private ResponseProcessor $responseProcessor;

    /**
     * @var ServerRequestInterface
     */
    private ServerRequestInterface $request;

    protected function __construct()
    {
        register_shutdown_function([$this, 'tearDown']);
    }

    /**
     * @return EventManager
     */
    public function getEventManager(): EventManager
    {
        return $this->eventManager;
    }

    /**
     * @param ResponseProcessor $responseProcessor
     */
    public function injectResponseProcessor(ResponseProcessor $responseProcessor)
    {
        $this->responseProcessor = $responseProcessor;
    }

    /**
     * @return ResponseProcessor
     */
    public function getResponseProcessor(): ResponseProcessor
    {
        return $this->responseProcessor;
    }

    /**
     * @param BundleManager $bundleManager
     */
    public function injectBundleManager(BundleManager $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }

    /**
     * @return BundleManager
     */
    public function getBundleManager(): BundleManager
    {
        return $this->bundleManager;
    }

    /**
     * @return LogManager
     */
    public function getLogManager(): LogManager
    {
        return $this->logManager;
    }

    /**
     * @api
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @param ServerRequestInterface $request
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param RequestHandler $requestHandler
     */
    public function setRequestHandler(RequestHandler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @return RequestHandler
     */
    public function getRequestHandler(): RequestHandler
    {
        return $this->requestHandler;
    }

    /**
     * @return ViewEngine
     */
    public function getViewEngine(): ViewEngine
    {
        return $this->viewEngine;
    }

    /**
     * @api
     * @param string $identifier
     * @param string $bundlePath
     * @param array $configuration
     * @return App
     * @throws Exception
     */
    public function registerBundle(string $identifier, string $bundlePath = '', array $configuration = []): App
    {
        $this->bundleManager->registerBundle($identifier, $bundlePath, $configuration);
        return $this;
    }

    /**
     * Start the app.
     * @api
     */
    public function start()
    {
        $this->bundleManager->startRegisteredBundles();
        $this->startRequestHandling();
        //tearDown will be executed in shutdown phase. This is a compromise for easier step-by-step integrations into
        //legacy projects. It allows you to do some code executions after the request handling of the app.
    }

    /**
     *
     */
    public function startRequestHandling()
    {
        $response = $this->requestHandler->handle($this->request);
        $this->responseProcessor->process($response);
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        $this->bundleManager->tearDownStartedBundles();
    }
}

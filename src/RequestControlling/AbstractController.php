<?php
namespace Merophp\Framework\RequestControlling;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Merophp\Framework\Cache\TransactionSessionCache;

use Merophp\Framework\ViewEngine\ViewEngineAwareInterface;
use Merophp\Framework\ViewEngine\ViewEngineTrait;
use Merophp\ViewEngine\ViewInterface;

use Merophp\Framework\EventManagement\EventManagerAwareInterface;
use Merophp\Framework\EventManagement\EventManagerTrait;

use Merophp\Framework\RequestControlling\Events\{
	AfterExecuteActionEvent,
	BeforeExecuteActionEvent
};

abstract class AbstractController implements ControllerInterface, EventManagerAwareInterface, ViewEngineAwareInterface
{
    use EventManagerTrait;
    use ViewEngineTrait;

    /**
     * View
     *
     * @var ?ViewInterface
     */
    protected ?ViewInterface $view = null;

    /**
     * @var ?TransactionSessionCache
     */
    protected ?TransactionSessionCache $transactionCache = null;


    /**
     * cached values
     *
     * @var array
     */
    protected array $cached = [];

    /**
     * @var ?ServerRequestInterface
     */
    protected ?ServerRequestInterface $request = null;

    /**
     * @var ?ResponseInterface
     */
    protected ?ResponseInterface $response = null;

    /**
     * @var false|string
     */
    protected $cacheHash;

    /**
     * @param TransactionSessionCache $transactionSessionCache
     */
    public function injectTransactionCache(TransactionSessionCache $transactionSessionCache)
    {
        $this->transactionCache = $transactionSessionCache;
    }

    /**
     * @param string $actionName
     * @param array $arguments
     */
    public function beforeExecuteAction(string $actionName, array $arguments = [])
    {
		$this->request = array_shift($arguments);
		$this->response = array_shift($arguments);

        if(isset($this->request->getQueryParams()['c'])){
            //get the old cached data
            $this->transactionCache->setCacheHash($this->request->getQueryParams()['c']);
            $this->cached = $this->transactionCache->grab();
        }

        //prepare new transaction cache
        $this->cacheHash = hash('sha256', uniqid());
        $this->transactionCache->setCacheHash($this->cacheHash);

        $this->view = $this->viewEngine->initializeView();

		$this->eventManager->dispatch(new BeforeExecuteActionEvent(get_class($this), $actionName, $arguments));
    }

    /**
     * @param string $actionName
     * @param array $arguments
     * @return ResponseInterface
     */
    public function afterExecuteAction(string $actionName, array $arguments = []): ResponseInterface
    {
        $this->eventManager->dispatch(new AfterExecuteActionEvent(get_class($this), $actionName, $arguments));

        $this->response = $this->response->withHeader('Content-Type', $this->view->getContentType());

        $this->response->getBody()->write($this->view->render());
        return $this->response;
    }
}

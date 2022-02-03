<?php
declare(strict_types=1);

namespace Merophp\Framework\EventManagement;

use Closure;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

use Yiisoft\EventDispatcher\Provider\CompositeProvider;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\EventDispatcher\Provider\ListenerCollection;

use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Merophp\Singleton\Singleton;

class EventManager extends Singleton implements EventDispatcherInterface
{

	/**
	 * @var ?CompositeProvider
	 */
	private ?CompositeProvider $compositeProvider = null;

	/**
	 * @param CompositeProvider $compositeProvider
	 */
	public function injectCompositeProvider(CompositeProvider $compositeProvider){
		$this->compositeProvider = $compositeProvider;
	}

	/**
	 * @param callable|array $listener
	 * @param string ...$eventClassNames
	 */
	public function addEventListeners($listener, string ...$eventClassNames){
		$listenerCollection = new ListenerCollection;

		if(is_array($listener)) $listener = Closure::fromCallable($listener);

		$listenerProvider = new Provider(
			$listenerCollection->add($listener, ...$eventClassNames)
		);

		$this->compositeProvider->attach($listenerProvider);
	}

	/**
	 * @param object $event
	 * @return object
	 */
	public function dispatch(object $event): object
	{
		$dispatcher = new Dispatcher($this->compositeProvider);
		return $dispatcher->dispatch($event);
	}
}

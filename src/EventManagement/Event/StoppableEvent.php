<?php
namespace Merophp\Framework\EventManagement\Event;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class StoppableEvent implements StoppableEventInterface
{

    /**
     * @var bool
     */
	protected bool $propagationStoppedFlag = false;

    /**
     *
     */
	public function stopPropagation()
    {
		$this->propagationStoppedFlag = true;
	}

	public function isPropagationStopped(): bool
    {
		return $this->propagationStoppedFlag;
	}
}

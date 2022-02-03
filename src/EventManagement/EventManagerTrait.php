<?php

namespace Merophp\Framework\EventManagement;

/**
 * Trait for classes which depends on the event manager.
 * @author Robert Becker
 */
trait EventManagerTrait
{
    protected ?EventManager $eventManager = null;

    public function injectEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }
}

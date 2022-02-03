<?php

namespace Merophp\Framework\EventManagement;

interface EventManagerAwareInterface
{
    public function injectEventManager(EventManager $eventManager);
}

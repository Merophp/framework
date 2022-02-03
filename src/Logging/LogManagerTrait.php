<?php

namespace Merophp\Framework\Logging;

/**
 * Trait for classes which depends on the logger manager.
 * @author Robert Becker
 */
trait LogManagerTrait
{
    /**
     * @var ?LogManager
     */
    private ?LogManager $logManager = null;

    /**
     * @param LogManager $logManager
     */
    public function injectLogManager(LogManager $logManager)
    {
        $this->logManager = $logManager;
    }
}

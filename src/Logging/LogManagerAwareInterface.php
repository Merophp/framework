<?php

namespace Merophp\Framework\Logging;

interface LogManagerAwareInterface
{
    public function injectLogManager(LogManager $logManager);
}

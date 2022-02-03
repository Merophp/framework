<?php

namespace Merophp\Framework\ViewEngine;

/**
 * @author Robert Becker
 */
trait ViewEngineTrait
{
    protected ?ViewEngine $viewEngine = null;

    public function injectViewEngine(ViewEngine $viewEngine)
    {
        $this->viewEngine = $viewEngine;
    }
}

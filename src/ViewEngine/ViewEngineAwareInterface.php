<?php

namespace Merophp\Framework\ViewEngine;

interface ViewEngineAwareInterface
{
    public function injectViewEngine(ViewEngine $viewEngine);
}

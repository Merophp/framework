<?php

namespace Merophp\Framework\Routing;

interface RouterAwareInterface
{
    public function injectRouter(Router $router);
}

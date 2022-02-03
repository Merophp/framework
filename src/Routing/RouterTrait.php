<?php

namespace Merophp\Framework\Routing;

/**
 * Trait for classes which depends on the router.
 * @author Robert Becker
 */
trait RouterTrait
{
    protected ?Router $router = null;

    public function injectRouter(Router $router)
    {
        $this->router = $router;
    }
}

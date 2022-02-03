<?php

namespace OrgaA\BundleA\Bootstrapping;

use Merophp\Framework\BundleManagement\BundleBootstrapper\AbstractBundleBootstrapper;

class Bootstrapper extends AbstractBundleBootstrapper
{

    public function setup()
    {
        $this->app->getRouter()->addRoutes(
            new \Merophp\Router\Routes\PostRoute('/test/{var1}/foo/{var2}', [\OrgaA\BundleA\Controlling\FooController::class,'barAction'])
        );
    }

    public function tearDown()
    {
    }
}

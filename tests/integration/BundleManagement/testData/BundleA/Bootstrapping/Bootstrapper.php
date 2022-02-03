<?php

namespace CompanyA\BundleA\Bootstrapping;

use Merophp\Framework\BundleManagement\BundleBootstrapper\AbstractBundleBootstrapper;

class Bootstrapper extends AbstractBundleBootstrapper
{

    public function setup()
    {
        $this->app->registerBundle('CompanyA\\BundleC', dirname(__DIR__,2).'/BundleC/');
        echo 'SetupA';
    }

    public function tearDown()
    {
        echo 'TearDownA';
    }
}

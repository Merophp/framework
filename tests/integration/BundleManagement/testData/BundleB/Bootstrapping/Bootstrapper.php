<?php

namespace CompanyA\BundleB\Bootstrapping;

use Merophp\Framework\BundleManagement\BundleBootstrapper\AbstractBundleBootstrapper;

class Bootstrapper extends AbstractBundleBootstrapper
{

    public function setup()
    {
        echo 'SetupB';
    }

    public function tearDown()
    {
        echo 'TearDownB';
    }
}

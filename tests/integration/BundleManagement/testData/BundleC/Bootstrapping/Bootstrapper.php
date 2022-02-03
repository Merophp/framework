<?php

namespace CompanyA\BundleC\Bootstrapping;

use Merophp\Framework\BundleManagement\BundleBootstrapper\AbstractBundleBootstrapper;

class Bootstrapper extends AbstractBundleBootstrapper
{

    public function setup()
    {
        echo 'SetupC';
    }

    public function tearDown()
    {
        echo 'TearDownC';
    }
}

<?php
namespace Merophp\Framework\BundleManagement\BundleBootstrapper\Factory;

use Merophp\BundleManager\BundleBootstrapper\Factory\BundleBootstrapperFactory as ParentBundleBootstrapperFactory;
use Merophp\ObjectManager\ObjectManager;

/**
 * Extends the original bootstrapper factory to instantiate bootstrappers with the object manager.
 *
 * @author Robert Becker
 */
class BundleBootstrapperFactory extends ParentBundleBootstrapperFactory
{
    protected function instantiateBundleBootstrapper(string $bootstrapperClassName)
    {
        return ObjectManager::get(
            $bootstrapperClassName
        );
    }
}

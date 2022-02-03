<?php
namespace Merophp\Framework\Autoloader;

use Merophp\Autoloader\Autoloader as RealAutoloader;
use Merophp\Autoloader\NamespacePrefix\Collection\NamespacePrefixCollection;
use Merophp\Autoloader\NamespacePrefix\NamespacePrefix;
use Merophp\Autoloader\NamespacePrefix\Provider\CompoundNamespacePrefixProvider;
use Merophp\Autoloader\NamespacePrefix\Provider\NamespacePrefixProvider;
use Merophp\Framework\Logging\LogManagerAwareInterface;
use Merophp\Framework\Logging\LogManagerTrait;
use Merophp\Singleton\Singleton;

/**
 * Autoloader facade for the real autoloader from merophp/autoloader
 */
final class Autoloader extends Singleton implements LogManagerAwareInterface
{
    use LogManagerTrait;

    private CompoundNamespacePrefixProvider $compoundNamespacePrefixProvider;

    protected function __construct()
    {
        $this->compoundNamespacePrefixProvider = new CompoundNamespacePrefixProvider;
        new RealAutoloader($this->compoundNamespacePrefixProvider);
    }

    /**
     * @param string $identifier
     * @param string $path
     */
    public function registerNamespacePrefix(string $identifier, string $path)
    {
        $newCollection = new NamespacePrefixCollection;
        $newCollection->add(new NamespacePrefix($identifier, $path));
        $this->compoundNamespacePrefixProvider->attach(new NamespacePrefixProvider($newCollection));
        $this->logManager->getLogger('framework')->debug(sprintf("Namespace prefix added for autoloading [%s => %s].", $identifier, $path));
    }
}

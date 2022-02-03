<?php
namespace Merophp\Framework\BundleManagement;

use Exception;
use Merophp\BundleManager\Collection\BundleCollection;
use Merophp\BundleManager\Provider\BundleProvider;
use Merophp\BundleManager\BundleManager as RealBundleManager;
use Merophp\BundleManager\Bundle;

use Merophp\Framework\BundleManagement\Event\{
	BundleRegisteredEvent,
    BundleStartedEvent,
    BundleShuttedDownEvent,
	AllBundlesStartedEvent,
	AllBundlesShuttedDownEvent
};

use Merophp\Framework\BundleManagement\BundleBootstrapper\Factory\BundleBootstrapperFactory;
use Merophp\Framework\EventManagement\EventManagerAwareInterface;
use Merophp\Framework\EventManagement\EventManagerTrait;
use Merophp\Framework\Logging\LogManagerAwareInterface;
use Merophp\Framework\Logging\LogManagerTrait;
use Merophp\Singleton\Singleton;

/**
 * Bundle manager facade for the real bundle manager from merophp/bundle-manager
 *
 * @author Robert Becker
 */
class BundleManager extends Singleton implements LogManagerAwareInterface, EventManagerAwareInterface
{
    use LogManagerTrait;
    use EventManagerTrait;

    /**
     * @var RealBundleManager
     */
    protected RealBundleManager $realBundleManager;

    /**
     * @var BundleCollection
     */
    protected BundleCollection $bundleCollection;

    protected function __construct()
    {
        $this->bundleCollection = new BundleCollection();
        register_shutdown_function([$this, 'tearDownStartedBundles']);
        $this->realBundleManager = new RealBundleManager(
            new BundleProvider($this->bundleCollection)
        );
    }

    /**
	 * @param BundleBootstrapperFactory $bundleBootstrapperFactory
	 */
	public function injectBundleBootstrapperFactory(BundleBootstrapperFactory $bundleBootstrapperFactory): void
    {
        $this->realBundleManager->setBundleBootstrapperFactory($bundleBootstrapperFactory);
	}

    /**
     * @param string $identifier The identifier of the bundle.
     * @param string $bundlePath The path of the bundle.
     * @param array $configuration
     * @throws Exception
     */
	public function registerBundle(string $identifier, string $bundlePath = '', array $configuration = []): void
    {
		$newBundle = new Bundle($identifier, $configuration);

		if($bundlePath && isset($GLOBALS['autoloader'])){
			$GLOBALS['autoloader']->registerNamespacePrefix(
				$identifier,
                $bundlePath
			);
		}
        $this->bundleCollection->add($newBundle);

        $this->logManager->getLogger('framework')->debug(sprintf("Bundle registered [%s].", $identifier));

		$this->eventManager->dispatch(new BundleRegisteredEvent($newBundle));
	}

    /**
     * Start all registered bundles.
     */
    public function startRegisteredBundles(): void
    {
        $th1s = $this;
        $this->realBundleManager->startRegisteredBundles(function(Bundle $bundle) use ($th1s) {
            $th1s->logManager->getLogger('framework')->debug(sprintf("Bundle started [%s].", $bundle->getIdentifier()));
            $th1s->eventManager->dispatch(new BundleStartedEvent($bundle));
        });
        $this->logManager->getLogger('framework')->debug("All bundles started [%s].");
        $this->eventManager->dispatch(new AllBundlesStartedEvent());
    }

    /**
     * Tear down all started bundles.
     */
    public function tearDownStartedBundles(): void
    {
        $th1s = $this;
        $this->realBundleManager->tearDownStartedBundles(function(Bundle $bundle) use ($th1s) {
            $th1s->logManager->getLogger('framework')->debug(sprintf("Bundle teared down [%s].", $bundle->getIdentifier()));
            $th1s->eventManager->dispatch(new BundleShuttedDownEvent($bundle));
        });
        $this->logManager->getLogger('framework')->debug("All bundles teared down [%s].");
        $this->eventManager->dispatch(new AllBundlesShuttedDownEvent());
    }
}

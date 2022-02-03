<?php
namespace Merophp\Framework\BundleManagement\BundleBootstrapper;

use Merophp\BundleManager\BundleBootstrapper\BundleBootstrapperInterface;

use Merophp\Framework\App;

abstract class AbstractBundleBootstrapper implements BundleBootstrapperInterface
{

	/**
	 * @var array
	 */
	protected array $configuration = [];

    /**
     * @var App
     */
    protected App $app;

    /**
     * @param App $app
     */
    public function injectApp(App $app): void
    {
        $this->app = $app;
    }

	/**
	 *
	 */
	public function setConfiguration(array $configuration = []): void
    {
		$this->configuration = $configuration;
	}
}

<?php
namespace Merophp\Framework\ViewEngine;

use Exception;
use Merophp\Singleton\Singleton;

use Merophp\ViewEngine\ViewEngine as RealViewEngine;
use Merophp\ViewEngine\ViewInterface;
use Merophp\ViewEngine\ViewPlugin\Collection\ViewPluginCollection;
use Merophp\ViewEngine\ViewPlugin\Provider\ViewPluginProvider;
use Merophp\ViewEngine\ViewPlugin\ViewPlugin;

/**
 * @author Robert Becker
 */
final class ViewEngine extends Singleton
{
    /**
     * @var RealViewEngine 
     */
    private RealViewEngine $realViewEngine;

    /**
     * @var ViewPluginCollection 
     */
    private ViewPluginCollection $viewPluginCollection;

    /**
     * 
     */
    protected function __construct()
    {
        $this->viewPluginCollection = new ViewPluginCollection();
        $this->realViewEngine = new RealViewEngine(new ViewPluginProvider($this->viewPluginCollection));
    }

    /**
     * @param ViewPlugin ...$viewPlugins
     */
    public function registerViewPlugins(ViewPlugin ...$viewPlugins)
    {
        $this->viewPluginCollection->addMultiple($viewPlugins);
    }

    /**
     * @param string $viewType
     * @return ViewInterface
     * @throws Exception
     */
    public function initializeView(string $viewType = ''): ViewInterface
    {
        return $this->realViewEngine->initializeView($viewType);
    }

    /**
     * @throws Exception
     */
    public function renderView(ViewInterface $view): string
    {
        return $this->realViewEngine->renderView($view);
    }
}

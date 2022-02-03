<?php
namespace Merophp\Framework\BundleManagement\Event;

use Merophp\BundleManager\Bundle;

class BundleEvent
{

    /**
     * @var Bundle
     */
	protected Bundle $bundle;

    /**
     * @param Bundle $bundle
     */
	public function __construct(Bundle $bundle)
    {
		$this->bundle = $bundle;
	}

	public function getBundle(): Bundle
    {
	    return $this->bundle;
    }
}

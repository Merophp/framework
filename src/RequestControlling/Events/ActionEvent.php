<?php
namespace Merophp\Framework\RequestControlling\Events;

class ActionEvent{

    /**
     * @var string
     */
	protected $controllerName;

    /**
     * @var string
     */
	protected $actionName;

    /**
     * @var array
     */
	protected $actionArguments;

    /**
     * @param string $controllerName
     * @param string $actionName
     * @param array $actionArguments
     */
	public function __construct(string $controllerName,string $actionName, array $actionArguments = [])
    {
		$this->controllerName = $controllerName;
		$this->actionName = $actionName;
		$this->actionArguments = $actionArguments;
	}

    /**
     * @return string
     */
	public function getControllerName(): string
    {
		return $this->controllerName;
	}

    /**
     * @return string
     */
	public function getActionName(): string
    {
		return $this->actionName;
	}

    /**
     * @return array
     */
	public function getActionArguments(): array
    {
		return $this->actionArguments;
	}
}

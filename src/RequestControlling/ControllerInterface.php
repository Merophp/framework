<?php
namespace Merophp\Framework\RequestControlling;

use Psr\Http\Message\ResponseInterface;

interface ControllerInterface
{

    /**
     * This method has to be executed before the controller action will be executed.
     *
     * @param string $actionName
     * @param array $arguments
     */
    public function beforeExecuteAction(string $actionName, array $arguments = []);

    /**
     * This method has to be executed after the controller action will be executed.
     *
     * @param string $actionName
     * @param array $arguments
     */
    public function afterExecuteAction(string $actionName, array $arguments = []): ResponseInterface;
}

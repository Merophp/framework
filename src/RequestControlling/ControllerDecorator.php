<?php
namespace Merophp\Framework\RequestControlling;


class ControllerDecorator
{

    /**
     * @var ?object
     */
    protected ?object $wrappee = null;

    /**
     *
     */
    public function __construct($wrappee)
    {
        $this->wrappee = $wrappee;
    }

    /**
     *
     */
    public function __call($methodName, $arguments)
    {
        $this->wrappee->beforeExecuteAction($methodName, $arguments);
        call_user_func_array([$this->wrappee, $methodName], array_slice($arguments, 2));
        return $this->wrappee->afterExecuteAction($methodName, $arguments);
    }
}

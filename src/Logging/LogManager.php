<?php

namespace Merophp\Framework\Logging;

use Merophp\Singleton\SingletonInterface;
use Merophp\Singleton\SingletonTrait;

/**
 * @author Dorian Zinner, Robert Becker
 */
class LogManager extends \Merophp\LogManager\LogManager implements SingletonInterface
{
    use SingletonTrait;
}
